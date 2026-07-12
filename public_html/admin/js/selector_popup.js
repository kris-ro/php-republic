(function(){
  document.addEventListener('click', e => {
    if (e.target.closest('a#selector-popup-closer')) {
      document.querySelector('iframe#selector-popup').src = 'about:blank';
      document.querySelector('#selector-popup-loader').classList.toggle('active');
    }

    if (e.target.closest('.slim-selector-trigger')) {
      loadPopupSelector(e);
    }

    if (e.target.closest('a.slim-select[data-value]')) {
      populateRequestedValue(e);
    }
  });

  let loadPopupSelector = e => {
    if (!(document.querySelector('iframe#selector-popup').src == 'about:blank')) {
      return;
    }

    document.querySelector('iframe#selector-popup').src = e.target.getAttribute('data-source');
    
    setTimeout(() => {
      let loader = document.querySelector('#selector-popup-loader');
      loader.style.display = 'block';
      void loader.offsetHeight;
      loader.classList.add('active');
    }, 500);
  };

  let populateRequestedValue = e => {
    let value = e.target.getAttribute('data-value') || e.target.parentElement.getAttribute('data-value');

    const searchParams = new URLSearchParams(window.location.search);

    let targetElement = searchParams.get('target');

    window.parent.document.querySelector('iframe#selector-popup').src = 'about:blank';
    window.parent.document.querySelector('#selector-popup-loader').classList.toggle('active');

    if (window.parent.document.querySelector('#' + targetElement)) {
      let replace = window.parent.document.querySelector('#' + targetElement).getAttribute('data-replace');

      if (replace) {
        let regExp = new RegExp(replace);
        value = value.replace(regExp, '');
      }

      window.parent.document.querySelector('#' + targetElement).value = value;

      if (window.parent.document.querySelector('#' + targetElement).classList.contains('table-search')) {

        let tableSearchElement = window.parent.document.querySelector('#' + targetElement);

        let listContainer = tableSearchElement.closest('.list-container');
        if (!listContainer) {
          return true;
        }
    
        let listContent = tableSearchElement.closest('.list-content');
        if (!listContent || !listContent.id) {
          return true;
        }

        window.parent.filterList(listContainer, listContent);

      }
    }
  };
})();
