
const custom_select = Array.from(document.querySelectorAll('.custom_select__current'));

custom_select.forEach((el) => {
    el.addEventListener('click', () => {
        if (el.closest('.custom_select').classList.contains('custom_select_open')) {
            el.closest('.custom_select').classList.remove('custom_select_open');
        } else {
            el.closest('.custom_select').classList.add('custom_select_open');
        }
    })
});
for (const option of document.querySelectorAll(".custom_select__item")) {
    option.addEventListener('click', function() {
        if (!this.classList.contains('custom_select__item_active')) {
            const parentContainer = this.closest('.custom_select');
            parentContainer.querySelector('.custom_select__item_active').classList.remove('custom_select__item_active');
            parentContainer.classList.remove('custom_select_open');
            this.classList.add('custom_select__item_active');
            parentContainer.querySelector('.custom_select__current').textContent = this.textContent;
            setAstrology.setParamForm(parentContainer.getAttribute('data-name'),this.getAttribute('data-val'));
        }
    })
}
window.addEventListener('click', function(e) {
   for (const select of document.querySelectorAll('.custom_select')) {
        if (!select.contains(e.target)) {
            select.classList.remove('custom_select_open');
        }
    }
});
class Astrology {
    constructor() {
        this.step = document.getElementById('firstStep').value;
        this.stepMax = document.getElementById('firstStep').value;
    }
    nextStep(){
        if(this.step == 9){
            return false;
        }
        document.querySelector('.form__item[data-step="' + this.step + '"]').classList.remove('active');
        this.step++;
        
        document.querySelector('.form__item[data-step="' + this.step + '"]').classList.add('active');

    }
    prevStep(){
        if(this.step == 1){
            return false;
        }
        document.querySelector('.form__item[data-step="' + this.step + '"]').classList.remove('active');
        this.step--;
        document.querySelector('.form__item[data-step="' + this.step + '"]').classList.add('active');
    }
    setParamForm(name,val) {
        this[name] = val;
        const allSelects = Array.from(document.querySelectorAll('.form__item[data-step="' + this.step + '"] .custom_select__item_active'));
        const emptySelect = allSelects.filter((el) => !el.dataset.val);
        if(!emptySelect.length){
            this.nextStep();
            if(this.stepMax < this.step){
                this.stepMax = this.step;
            }
        }
    }
    setParam(name,val) {
        this[name] = val;
        console.log(this,this.step)
        this.nextStep();
        if(this.stepMax < this.step){
            this.stepMax = this.step;
        }
    }
}
const setAstrology = new Astrology();
document.querySelector('.form__item[data-step="' + setAstrology.step + '"]').classList.add('active');

document.querySelector('.header__next').addEventListener('click', () => {
    setAstrology.nextStep();
    if(document.querySelector('.form__item.active').dataset.step >= setAstrology.stepMax){
        document.querySelector('.header__next').classList.remove('active');   
    }
    document.querySelector('.header__prev').classList.add('active');
})
document.querySelector('.header__prev').addEventListener('click', () => {
    setAstrology.prevStep();
    if(setAstrology.step == 1){
        document.querySelector('.header__prev').classList.remove('active');
    } 
    document.querySelector('.header__next').classList.add('active');   
})
//step 1
document.querySelectorAll('.form__item__zodiac__item').forEach((el) => {
    el.addEventListener('click', () => {
        const val = el.dataset.zodiac;
        if(document.querySelector('.form__item__zodiac__item.active')){
            document.querySelector('.form__item__zodiac__item.active').classList.remove('active');
        }
        el.classList.add('active');
        if(document.querySelector('.form__item__birthday__months.active')){
            document.querySelector('.form__item__birthday__months.active').classList.remove('active');
        }
        document.querySelector('.form__item__birthday__months[data-zodiac="' + val + '"]').classList.add('active');
        setAstrology.setParam('zodiac',val);
        if(!document.querySelector('.header__prev.active')){
            document.querySelector('.header__prev').classList.add('active');
        }
    })
});
//step 2
document.querySelectorAll('.form__item__birthday__month__days a').forEach((el) => {
    el.addEventListener('click', () => {
        const val = el.dataset.day;
        setAstrology.setParam('day',val);
        if(document.querySelector('.form__item__birthday__month__days a.active')){
            document.querySelector('.form__item__birthday__month__days a.active').classList.remove('active');
        }
        el.classList.add('active');
    })
});
//step 3
document.querySelectorAll('.form__item__decade a').forEach((el) => {
    el.addEventListener('click', () => {
        const val = el.dataset.decade;
        if(document.querySelector('.form__item__decade a.active')){
            document.querySelector('.form__item__decade a.active').classList.remove('active');
        }
        el.classList.add('active');
        if(document.querySelector('.form__item__year__wrapper.active')){
            document.querySelector('.form__item__year__wrapper.active').classList.remove('active');
        }
        document.querySelector('.form__item__year__wrapper[data-decade="' + val + '"]').classList.add('active');
        setAstrology.setParam('decade',val);
    })
});
//step 4
document.querySelectorAll('.form__item__year__wrapper a').forEach((el) => {
    el.addEventListener('click', () => {
        const val = el.dataset.year;
        setAstrology.setParam('year',val);
        if(document.querySelector('.form__item__year__wrapper a.active')){
            document.querySelector('.form__item__year__wrapper a.active').classList.remove('active');
        }
        el.classList.add('active');
    })
});
//step 5
document.querySelector('.form__item__time__skip').addEventListener('click', () => {
    setAstrology.nextStep();
})
//step 7
document.querySelectorAll('.form__item__gender__item').forEach((el) => {
    el.addEventListener('click', () => {
        const val = el.dataset.gender;
        setAstrology.setParam('gender',val);
        if(document.querySelector('.form__item__gender__item.active')){
            document.querySelector('.form__item__gender__item.active').classList.remove('active');
        }
        el.classList.add('active');
    })
});
//step 8
document.querySelector('.form__item[data-step="8"] .form__item__btn').addEventListener('click', () => {
    const val = document.querySelector('.form__item__input__val_name').value;
    if(val){
        setAstrology.setParam('name',val);
    }
})
