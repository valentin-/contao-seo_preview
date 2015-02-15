jQuery(document).ready(function($){
  $(document).on('keyup change cut paste','#ctrl_title',function(){
    $('#ctrl_pageTitle').change();
  })

  $('#ctrl_title').change();

  $(document).on('keyup change cut paste','#ctrl_pageTitle',function(){
    v = $(this).val() ? $(this).val() : $('#ctrl_title').val();
    c = v.length + $('.root_page_title').text().length;
    max = $('#seo_count .title .max').text();
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

    $('#seo_preview .title .page_title').empty().append(v);
    $('#seo_count .title .count').empty().append(c)
    $('#seo_count .title').removeClass('error ok warn').addClass(status);
  })

  $('#ctrl_pageTitle').change();


  $(document).on('keyup change cut paste','#ctrl_description',function(){
    v = $(this).val();
    c = v.length;
    max = $('#seo_count .description .max').text();
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
      $('#seo_preview .description').empty().append(v);
    } else {
      $('#seo_preview .description').empty().append($('#seo_preview .description').data('empty'));
    }
    $('#seo_count .description .count').empty().append(c);
    $('#seo_count .description').removeClass('error ok warn').addClass(status);
  })

  $('#ctrl_description').change();


})