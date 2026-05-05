(function(){
  let selectorTriggers = document.querySelectorAll('.slim-selector-trigger');

  if (selectorTriggers.length) {
    selectorTriggers.forEach(selector => {
      selector.addEventListener('click', e => {
        setTimeout(() => {
          document.querySelector('#selector-popup-loader').classList.add('active');
        }, 10);
      });
    });
  }

  document.addEventListener('click', e => {
    let target = e.target.closest('a#selector-popup-closer');

    if(!target){
      return;
    }

    document.querySelector('#selector-popup-loader').classList.toggle('active');
  });
})();