(function () {
  const copyBtn = document.querySelector('#copy-btn');

  if (copyBtn) {
    const textArea = document.querySelector('#token-copy');

    copyBtn.addEventListener('click', async (e) => {
      try {
        e.preventDefault();
        const textToCopy = textArea.value;
        await navigator.clipboard.writeText(textToCopy);
        copyBtn.innerText = '<i class="bi bi-check-all me-2"></i> ' + copyBtn.getAttribute('data-default-copied');
        setTimeout(() => {
          copyBtn.innerText = '<i class="bi bi-copy me-2"></i> ' + copyBtn.getAttribute('data-default-text');
        }, 2000);

      } catch (err) {
  //      console.error('Failed to copy: ', err);
        alert('Failed to copy.');
      }
    });
  }
})();