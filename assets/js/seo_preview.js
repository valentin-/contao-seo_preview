var seoTitle, seoPageTitle, seoDescription, seoPreview, seoCount;

jQuery(document).ready(function($){

  $(document).on('keyup change cut paste','[name^="title"]',function(){
    updateVars($(this));
    seoPageTitle.change();
  })

  //$('[name^="title"]').change();

  $(document).on('keyup change cut paste','[name^="pageTitle"]',function(){
    updateVars($(this));
    v = $(this).val() ? $(this).val() : seoTitle.val();
    c = v.length + seoPreview.find('.root_page_title').text().length;
    max = seoCount.find('.title .max').text();
    l = max-c;

    if(l < 0) {
      status = 'error';
    } else if(l < 10) {
      status = 'ok';
    } else if(l < 20) {
      status = 'warn';
    } else {
      status = '';
    }

    seoPreview.find('.page_title').empty().append(v); 
    seoCount.find('.title .count').empty().append(c)
    seoCount.find('.title').removeClass('error ok warn').addClass(status);
  })

  //$('[name^="pageTitle"]').change();

  $(document).on('keyup change cut paste','[name^="description"]',function(){
    updateVars($(this));
    v = $(this).val();
    c = v.length;
    max = seoCount.find('.description .max').text();
    l = max-c;

    if(l < 0) {
      status = 'error';
    } else if(l < 30) {
      status = 'ok';
    } else if(l < 50) {
      status = 'warn';
    } else {
      status = '';
    }

    if(v) {
      seoPreview.find('.description').empty().append(v);
    } else {
      seoPreview.find('.description').empty().append(seoPreview.find('.description').data('empty'));
    }
    seoCount.find('.description .count').empty().append(c);
    seoCount.find('.description').removeClass('error ok warn').addClass(status);
  })

  $('[name^="description"]').change();

  function updateVars(el) {
    //editAll
    if($('[name^="pageTitle_"]').length || $('[name^="title_"]').length || $('[name^="description_"]').length) {
      p = el.closest('.tl_box, .tl_tbox');
      seoTitle = p.find('[name^="title"]');
      seoPageTitle = p.find('[name^="pageTitle"]');
      seoDescription = p.find('[name^="description"]');
      seoPreview = p.find('.seo_preview');
      seoCount = p.find('.seo_count');
    } else {
      seoTitle = $('[name^="title"]');
      seoPageTitle = $('[name^="pageTitle"]');
      seoDescription = $('[name^="description"]');
      seoPreview = $('.seo_preview');
      seoCount = $('.seo_count');
    }
  }

  $('a[data-show-preview]').mouseenter(function(){
    id = $(this).data('show-preview');
    $target = $('[data-seo-preview="'+id+'"').show();

    $target.css({
      position: 'absolute',
      top: 0
    })
  })

  $('a[data-show-preview]').mouseleave(function(){
    id = $(this).data('show-preview');
    $target = $('[data-seo-preview="'+id+'"').hide();
  })

})