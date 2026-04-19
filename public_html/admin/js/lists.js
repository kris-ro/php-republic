(function(){
  document.body.addEventListener('click', function(e){
    let target = e.target.closest('.page-list-sorter');

    if(!target){
      return true;
    }

    if (!target.href) {
      return true;
    }

    let listContainer = target.closest('.list-container');
    if (!listContainer) {
      return true;
    }

    let listContent = target.closest('.list-content');
    if (!listContent || !listContent.id) {
      return true;
    }

    e.preventDefault();

    loadList(listContainer, listContent, target.href);
  });

  let tableContainers = document.querySelectorAll('.list-container');
  if (tableContainers.length) {
    tableContainers.forEach(listContainer => {
      let listContent = listContainer.querySelector('.list-content');
      if (!listContent || !listContent.id) {
        return true;
      }

      setupListFilterEventListener(listContainer, listContent);
    });
  }
})();

function loadList(listContainer, listContent, path) {
  fetch(path).then(function (response) {
      return response.text();

    }).then(function (data) {
      let element = document.createElement('DIV');
      element.innerHTML = data;

      listContainer.innerHTML = '';
      listContainer.append(element.querySelector('#' + listContent.id));

      setupListFilterEventListener(listContainer, listContent);
    }).catch(function (err) {
//      console.warn('Something went wrong.', err);
    });
}

function setupListFilterEventListener(listContainer, listContent) {
  listContainer.querySelectorAll('input.table-search').forEach(input => {
    input.addEventListener('keyup', e => {
      if (e.isComposing || e.keyCode === 229) {
        return;
      }

      if (e.keyCode !== 13) { // enter
        return;
      }

      filterList(listContainer, listContent);
    });
  });

  listContainer.querySelectorAll('select.table-search').forEach(select => {
    select.addEventListener('change', e => {
      filterList(listContainer, listContent);
    });
  });

  listContainer.querySelectorAll('a.page-link').forEach(link => {
    link.addEventListener('click', e => {
      loadPage(e);
    });
  });
}

function loadPage(e) {
  let listContainer = e.target.closest('.list-container');
    if (!listContainer) {
      return true;
    }

    let listContent = e.target.closest('.list-content');
    if (!listContent || !listContent.id) {
      return true;
    }

    e.preventDefault();

    loadList(listContainer, listContent, e.target.href);
}

function filterList(listContainer, listContent) {
  let path = listContainer.getAttribute('data-list-address');

  document.querySelectorAll('.table-search').forEach(input => {
    if (input.value) {
      if (input.name === 'list_size') {
        path = path + encodeURIComponent(input.value) + '/';
      } else {
        path = path + encodeURIComponent(input.name) + '/' + encodeURIComponent(input.value) + '/';
      }
    }
  });
  console.log(path);
  loadList(listContainer, listContent, path);
}