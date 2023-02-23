if (document.querySelector('.js_main_form')) {
    // Set zodiac sign title
    document.querySelectorAll('.js_choose_zodiac_sign').forEach((el) => {
        el.addEventListener('click', () => {
            document.querySelector('.js_input_zodiac_sign').value = el.dataset.title;
        })
    });

    // Set birthday
    document.querySelectorAll('.js_choose_year_birth').forEach((el) => {
        el.addEventListener('click', () => {
            let year = el.dataset.year;

            let dayMonthOfBirthday;
            if (document.querySelector('.js_choose_birthday.active')) {
                dayMonthOfBirthday = document.querySelector('.js_choose_birthday.active').dataset.day;
            } else {
                dayMonthOfBirthday = document.querySelector('.form__item__birthday__months.active .js_choose_birthday').dataset.day;
            }

            document.querySelector('.js_input_birthday').value = dayMonthOfBirthday + '-' + year;
        })
    });


    // Choose hour of birth
    document.querySelectorAll('.js_choose_hour_birth').forEach((el) => {
        el.addEventListener('click', () => {
            let hour = el.dataset.val || '00';
            let minute = document.querySelector('.js_choose_minute_birth.custom_select__item_active').dataset.val || '00';
            let part = document.querySelector('.js_choose_part_birth.custom_select__item_active').dataset.val || 'AM';

            setTimeOfBirthday(hour, minute, part);
        })
    });

    // Choose minute of birth
    document.querySelectorAll('.js_choose_minute_birth').forEach((el) => {
        el.addEventListener('click', () => {
            let minute = el.dataset.val || '00';
            let hour = document.querySelector('.js_choose_hour_birth.custom_select__item_active').dataset.val || '00';
            let part = document.querySelector('.js_choose_part_birth.custom_select__item_active').dataset.val || 'AM';

            setTimeOfBirthday(hour, minute, part);
        })
    });

    // Choose part of tha day
    document.querySelectorAll('.js_choose_part_birth').forEach((el) => {
        el.addEventListener('click', () => {
            let partOfDay = el.dataset.val || 'AM';
            let hour = document.querySelector('.js_choose_hour_birth.custom_select__item_active').dataset.val || '00';
            let minute = document.querySelector('.js_choose_minute_birth.custom_select__item_active').dataset.val || '00';

            setTimeOfBirthday(hour, minute, partOfDay);
        })
    });

    // Set time of birthday
    function setTimeOfBirthday(hour, minute, partDay) {
        document.querySelector('.js_input_time_of_birthday').value = hour + ':' + minute + ' ' + partDay;
    }

    // Clean time of birth
    document.querySelector('.form__item__time__skip').addEventListener('click', () => {
        document.querySelector('.js_input_time_of_birthday').value = '00:00 AM';
    });

    // form__item__gender__item
    document.querySelectorAll('.form__item__gender__item').forEach((el) => {
        el.addEventListener('click', () => {
            document.querySelector('.js_input_gender').value = el.dataset.gender;
        })
    });


    // Ajax send form
    function postAjax(url, data, success) {
        let params = typeof data == 'string' ? data : Object.keys(data).map(
            function (k) {
                return encodeURIComponent(k) + '=' + encodeURIComponent(data[k])
            }
        ).join('&');

        let xhr = window.XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
        xhr.open('POST', url);
        xhr.onreadystatechange = function () {
            if (xhr.readyState > 3 && xhr.status == 200) {
                success(xhr.responseText);
            }
        };
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.send(params);
        return xhr;
    }

    // Send data to CRM
    const form = document.querySelector('.js_main_form');
    form.addEventListener('submit', function (e) {
        e.preventDefault();
        document.querySelector('.js_last_step .form__item__btn').classList.add('form__item__btn__inactive');
        let form_data = new FormData(form);
        console.log(form_data);
        postAjax('/wp-admin/admin-ajax.php?action=em_send_data_to_crm', {
            zodiac_sign: document.querySelector('.js_input_zodiac_sign').value,
            birthday: document.querySelector('.js_input_birthday').value,
            time_of_birth: document.querySelector('.js_input_time_of_birthday').value || '00:00 AM',
            country: $('#country').val(),
            state: document.querySelector('.js_input_state').value,
            city: $('#city').val(),
            gender: document.querySelector('.js_input_gender').value || document.querySelector('.form__item__gender__item').dataset.gender,
            first_name: document.querySelector('.form__item__input__val_name').value,
            email: document.querySelector('.form__item__input__val_email').value,
        }, function (data) {
            data = JSON.parse(data);
            if (data.status === 'ok') {
                window.location.href = data.thanks_page_url;
            } else {
                document.querySelector('.js_last_step .form__item__btn').classList.remove('form__item__btn__inactive');
            }
        });
    })

    $('#country').on('change',function(e){
        document.querySelector('#city').innerHTML = '<option value="">City</option>';
        
        //if user selected US
        if(e.target.value === 'US'){
            postAjax('/wp-admin/admin-ajax.php?action=em_get_states_by_country_iso2_code', {
                iso2: 'US',
            }, function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    document.querySelector('.form__item__place__state').classList.remove('custom_select_disable');
                    document.querySelector('.form__item__place__state').classList.remove('hidden');
                } else {
                    document.querySelector('.form__item__place__state').classList.add('hidden');
                }
                document.querySelector('.js_states_list').innerHTML = '';
                document.querySelector('.js_states_list').innerHTML = data.states_list;


                for (const option of document.querySelectorAll(".custom_select__item")) {
                    option.addEventListener('click', function () {
                        if (!this.classList.contains('custom_select__item_active')) {
                            const parentContainer = this.closest('.custom_select');
                            parentContainer.querySelector('.custom_select__item_active').classList.remove('custom_select__item_active');
                            parentContainer.classList.remove('custom_select_open');
                            this.classList.add('custom_select__item_active');
                            parentContainer.querySelector('.custom_select__current').textContent = this.textContent;
                        }
                    })
                }

            });
        }
        else {
            document.querySelector('.form__item__place__state').classList.add('hidden');
            document.querySelector('#city').classList.add('custom_select_disable');
            document.querySelector('.js_input_state').value = e.target.getAttribute("data-title") || '';
            let country_iso2 = e.target.value;
            let state_iso2 = null;

            postAjax('/wp-admin/admin-ajax.php?action=em_get_cities_by_state_and_country_iso2_code', {
                country_iso2,
                state_iso2,
            }, function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    document.querySelector('#city').classList.remove('custom_select_disable');
                } else {
                    document.querySelector('#city').classList.add('custom_select_disable');
                }
                document.querySelector('#city').innerHTML = '';
                document.querySelector('#city').innerHTML = data.cities_list;
            });
        }
    })

    $(document).ready(function() {
        $('#country').select2({
            theme: "dark",
            height:'100px',
        });
        $('#city').select2({
            theme: "dark",
            height:'100px',
        });
    })

    // Get state cities
    const state_container = document.querySelector('.js_states_list');
    state_container.addEventListener('click', function (e) {
        if (e.target.classList.contains('js_choose_state')) {
            $('#city').val('City');
            $('#city').change();
            document.querySelector('.js_input_state').value = e.target.getAttribute("data-title") || '';
            let country_iso2 = $('#country').val();
            let state_iso2 = e.target.getAttribute("data-val");

            postAjax('/wp-admin/admin-ajax.php?action=em_get_cities_by_state_and_country_iso2_code', {
                country_iso2: country_iso2,
                state_iso2: state_iso2,
            }, function (data) {
                data = JSON.parse(data);
                if (data.status === 'ok') {
                    document.querySelector('#city').classList.remove('custom_select_disable');
                } else {
                    document.querySelector('#city').classList.add('custom_select_disable');
                }
                document.querySelector('#city').innerHTML = '';
                document.querySelector('#city').innerHTML = data.cities_list;
            });
        }
    });

    $('#city').change(function (e) {
        if(e.target.value){
            setAstrology.nextStep();
        }
    })
}
