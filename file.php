// Dropzone.autoDiscover = false;
        // const dropzone = $("#image").dropzone({
            
        //     url: "{{ route('product.image.upload') }}",
        //     maxFiles: 10,
        //     paramName: 'image',
        //     addRemoveLinks: true,
        //     acceptedFiles: 'image/*',
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     },

        //     success: function(file, response) {
        //         // $("#image_id").val(response.image_id);

        //         var html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
        //             <input type="hidden" name="image-array[]" value="${response.image_id}">
        //                 <img src="${response.ImagePath}" class="card-img-top" alt="">
        //                 <div class="card-body">
                            
        //                     <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
        //                 </div>
        //             </div>
        //             </div>`;

        //         $("#product-gallery").append(html);
        //     }
        // });