<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
    <style>
        .preview-img{
            display: flex;
            justify-content: flex-start;
            flex-direction: row;
            flex-wrap: wrap;
            align-items: flex-start;
        }
    </style>
</head>
<main>
    <span>ทดสอบ</span>
    <section class="py-3 text-center container">
      <div class="row py-lg-3">
        <div class="col-lg-6 col-md-8 mx-auto">
            <form id="save-form" class="form-data">
                @csrf
                <div class="raw col-ms-12 pb-3">
                    <input class="form-control" type="file" id="fileInput" name="file_tep[]" multiple>
                </div>
                <input type="file" id="fileInput1" name="file_data[]" hidden>
                <!-- Preview Image Container -->
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-outline-primary" id="submitForm" type="submit">save</button>
                  </div>
            </form>
        </div>
      </div>
    </section>
    
    <div class="album py-3 bg-body-tertiary">
      <div class="container">
        <div class="row row-cols-12 row-cols-sm-12 g-3">
            <div class="col preview-img" id="previewContainer">
                
            </div>
        </div>
      </div>
    </div>
  
  </main>
<body>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@3.4.1/dist/js/bootstrap.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
        document.addEventListener("DOMContentLoaded", function() {
            
        });
        $(document).ready(function() {

            const fileInput = document.getElementById('fileInput');
            const fileInput1 = document.getElementById('fileInput1');
            const previewContainer = document.getElementById('previewContainer');

            $('#fileInput').on('change', function(event) {
                copyFilesAndPreview(event.target, fileInput1, previewContainer);
            });

             $('#previewContainer').on('click', function(event) {
                if (event.target.tagName === 'IMG') {
                    deleteFileAndPreview(event.target, fileInput1, previewContainer);
                }
            });
            $('.form-data').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: '/save',
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(data) {
                    },
                    error: function(err) {
                    }
                })
            });
        });
        function copyFilesAndPreview(sourceInput, targetInput, previewContainer) {
            if (sourceInput.files.length > 0) {
                const selectedFiles = sourceInput.files;
                const newFiles = [];

                for (const file of selectedFiles) {
                    newFiles.push(file);

                    // แสดงตัวอย่างรูปภาพ
                    const img = document.createElement('img');
                    img.src = URL.createObjectURL(file);
                    img.style.maxWidth = '200px';
                    img.style.margin = '5px';
                    img.setAttribute('data-name', file.name);
                    /* img.classList.add('preview-img'); */
                    previewContainer.appendChild(img);
                }

                if (targetInput.files) {
                    for (const file of targetInput.files) {
                        newFiles.push(file);
                    }
                }

                const dataTransfer = new DataTransfer();
                for (const file of newFiles) {
                    dataTransfer.items.add(file);
                }

                targetInput.files = dataTransfer.files;
            }
            console.log(targetInput.files);
        }

        function deleteFileAndPreview(imgElement, targetInput, previewContainer) {
            // ลบรูปภาพออกจากตัวอย่างรูปภาพ
            previewContainer.removeChild(imgElement);
            // ลบรูปภาพออกจาก fileInput1
            const dataName = imgElement.getAttribute('data-name');
            const updatedFiles = [...targetInput.files].filter(file => file.name !== dataName);
            const dataTransfer = new DataTransfer();
            for (const file of updatedFiles) {
                dataTransfer.items.add(file);
            }
            targetInput.files = dataTransfer.files;
        }
    </script>
</body>
</html>
