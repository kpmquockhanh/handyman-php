const loaderW=document.getElementById('loader')

  loaderW.classList.remove('hide');
  setTimeout(() => {
    loaderW.classList.add('hide');
    setTimeout(() => {
      loaderW.remove();
    }, 200);
  }, 200);