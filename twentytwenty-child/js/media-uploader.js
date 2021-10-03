jQuery(document).ready( function($){ 
    var mediaUploader;
    var imgs = $('#product_images_ids').val(); // save here the id's of uploaded imgs
    var tag;  

    /** Insert images with media uploader */
    $('.image-div').on('click', '.upload-button',function(e) {
        e.preventDefault();

        // get the current elemnts tag. used ahead by select function.
        tag = this.getAttribute('tag');   

        if( mediaUploader ){
            mediaUploader.open();
            return;
        }

    mediaUploader = wp.media.frames.file_frame =wp.media({
        title: 'Choose an Image',
        button: {
            text: 'Choose Image'
        },
        multiple:false
    });

    mediaUploader.on('select', function(){
        attachment = mediaUploader.state().get('selection').first().toJSON();
        $('#profile-picture-preview').css('background-image','url(' + attachment.url + ')');

        // keep the img id
        imgs +=  attachment.id +',';
        $('#product_images_ids').val( imgs );

        // change the div content. add notation that its not saved yet (btn value = clear img).
        let html = `<input type='button' tag=${tag} class='button button-secondary delete-button' 
                        value='Clear Image' style='color:red;border-color:red;' />
                    <input type='hidden' id='product_image_id-${tag}' name='product_image_id'  value=${attachment.id} />
                    <img width="150" height="150" src=${attachment.url} class="attachment-100x100 size-100x100" 
                        alt=${attachment.alt} ></img> `;
        $(`#img-${tag}`).html(html); 
    });

    mediaUploader.open();
    });

    /** Remove image */
    $('.image-div').on('click', '.delete-button',function(e) {
        e.preventDefault();
        console.log(this);

        // get the current elemnts tag. used ahead.
        tag = this.getAttribute('tag');

        // get the imgid to remove
        let imgid = $(`#product_image_id-${tag}`).val();    
        // update the input
        imgs = imgs.replace(`${imgid},`,'');
        $('#product_images_ids').val( imgs );

        // reset the div's content
        let html = `<input type='button'  tag=${tag} class='button button-secondary upload-button' 
                    value='Upload Product Image' tag=${tag} >`;
        $(`#img-${tag}`).html(html); 
    });


});