<?php
print_r($_POST);
print_r($_FILES);
?>

<form id="data" method="post" enctype="multipart/form-data">
    <input type="text" name="first" value="Bob" />
    <input type="text" name="middle" value="James" />
    <input type="text" name="last" value="Smith" />
    <input name="image" type="file" />
    <button>Submit</button>
</form>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
    $("form#data").submit(function(){

        var formData = new FormData(this);

        $.ajax({
            url: window.location.pathname,
            type: 'POST',
            data: formData,
            async: false,
            success: function (data) {
                alert(data)
            },
            cache: false,
            contentType: false,
            processData: false
        });
    });
</script>
