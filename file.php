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


        <div class="card mb-3">
                            <div class="card-body">
                                <h2 class="h4 mb-3">Media</h2>
                                <div id="image" class="dropzone dz-clickable">
                                    <div class="dz-message needsclick">
                                        <br>Drop files here or click to upload.<br><br>
                                    </div>
                                </div>
                            </div>
                        </div>




                        section('customJs')
    <script>
        $("#title").change(function() {
            element = $(this);
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',

                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }

            });
        });

        $("#productForm").submit(function(event) {
            event.preventDefault();
            var formArray = $(this).serializeArray();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route("products.store") }}',
                type: 'POST',
                data: formArray,
                dataType: 'json',

                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    window.location.href = "{{ route('products.index') }}";
                    if (response["status"] == true) {

                        $(".error").removeClass("invalid-feedback").html("");
                        $("input[type='text'], select, input[type='number']").removeClass("is-invalid");
                        
                        window.location.href = "{{ route('products.index') }}";
                    } else {
                        var errors = response['errors'];

                        $(".error").removeClass("invalid-feedback").html("");
                        $("input[type='text'], select, input[type='number']").removeClass("is-invalid");
                        $.each(errors, function(key, value) {
                            $("#" + key).addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(value);
                        });


                    }
                },
                error: function() {
                    console.log("something went wrong");
                }
            });
        });

       

        function deleteImage(id) {
            $("#image-row-" + id).remove();
            // $("#image_id").val("");
        }
    </script>
@endsection




@section('customJs')
    <script>
        $("#title").change(function() {
            element = $(this);
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route('getSlug') }}',
                type: 'get',
                data: {
                    title: element.val()
                },
                dataType: 'json',

                success: function(response) {
                    $("button[type='submit']").prop('disabled', false);
                    if (response["status"] == true) {
                        $("#slug").val(response["slug"]);
                    }
                }

            });
        });

        $("#productForm").submit(function(event) {
            event.preventDefault();
            var formArray = $(this).serializeArray();
            $("button[type='submit']").prop('disabled', true);
            $.ajax({
                url: '{{ route("products.store") }}',
                type: 'POST',
                data: formArray,
                dataType: 'json',

                success: function(response) {
                    $("button[type=submit]").prop('disabled', false);
                    window.location.href = "{{ route('products.index') }}";
                    if (response["status"] == true) {

                        $(".error").removeClass("invalid-feedback").html("");
                        $("input[type='text'], select, input[type='number']").removeClass("is-invalid");
                        
                        window.location.href = "{{ route('products.index') }}";
                    } else {
                        var errors = response['errors'];

                        $(".error").removeClass("invalid-feedback").html("");
                        $("input[type='text'], select, input[type='number']").removeClass("is-invalid");
                        $.each(errors, function(key, value) {
                            $("#" + key).addClass('is-invalid').siblings('p').addClass(
                                    'invalid-feedback')
                                .html(value);
                        });


                    }
                },
                error: function() {
                    console.log("something went wrong");
                }
            });
        });
Dropzone.autoDiscover = false;
        const dropzone = $("#image").dropzone({
            
            url: "{{ route('product.image.upload') }}",
            maxFiles: 10,
            paramName: 'image',
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },

            success: function(file, response) {
                // $("#image_id").val(response.image_id);

                var html = `<div class="col-md-3" id="image-row-${response.image_id}"><div class="card">
                    <input type="hidden" name="image-array[]" value="${response.image_id}">
                        <img src="${response.ImagePath}" class="card-img-top" alt="">
                        <div class="card-body">
                            
                            <a href="javascript:void(0)" onclick="deleteImage(${response.image_id})" class="btn btn-danger">Delete</a>
                        </div>
                    </div>
                    </div>`;

                $("#product-gallery").append(html);
            }
        });

       

        function deleteImage(id) {
            $("#image-row-" + id).remove();
            // $("#image_id").val("");
        }
    </script>
@endsection





<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->string('image');
            // $table->integer('sort_order')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};




<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function images()
    {
        return $this->belongsToMany(Product::class);
    }
}