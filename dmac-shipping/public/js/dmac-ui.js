(function(){
  function ready(fn){document.readyState!=='loading'?fn():document.addEventListener('DOMContentLoaded',fn);}
  ready(function(){
    if(!document.querySelector('.dmac-mobile-toggle')){
      var btn=document.createElement('button');
      btn.className='dmac-mobile-toggle';
      btn.type='button';
      btn.innerHTML='<i class="fa-solid fa-bars"></i>';
      btn.addEventListener('click',function(){document.body.classList.toggle('dmac-sidebar-open');});
      document.body.appendChild(btn);
    }
    document.addEventListener('click',function(e){
      if(window.innerWidth>1000) return;
      var side=document.querySelector('.sidebar');
      var toggle=document.querySelector('.dmac-mobile-toggle');
      if(document.body.classList.contains('dmac-sidebar-open') && side && !side.contains(e.target) && toggle && !toggle.contains(e.target)){
        document.body.classList.remove('dmac-sidebar-open');
      }
    });
    document.querySelectorAll('table').forEach(function(tbl){
      if(!tbl.closest('.table-responsive') && !tbl.closest('.employees-table-wrap')){
        var wrap=document.createElement('div');
        wrap.className='table-responsive';
        tbl.parentNode.insertBefore(wrap,tbl);wrap.appendChild(tbl);
      }
    });
  });
})();
