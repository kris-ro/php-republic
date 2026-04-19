(function(){
  let showMessages = () => {
    let popups = [];

    ['#errorModal', '#warningModal', '#infoModal', '#successModal'].forEach(id => {
      if (document.querySelector(id)) {
        popups[id] = new bootstrap.Modal(id);
        popups[id].show();
      }
    });
  };

  showMessages();
})();


