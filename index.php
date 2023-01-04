<?php
include_once 'api/function.php';
$documents = query_getData($conn, "SELECT * from document");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Documents</title>
    <link rel="icon" type="image/png" href="<?= BASE_URL ?>assets/favicon.png" />
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/bootstrap.min.css">
</head>

<body>

    <div class="container">
        <div class="card mt-4">
            <div class="card-header">Uplaod document</div>
            <div class="card-body">
                <div class="alert alert-danger" id="error-block" role="alert" style="display: none;">
                    <h4 class="alert-heading">Document not able to upload!</h4>
                    <ol id="error-list">
                        <li></li>
                    </ol>
                    <hr>
                    <p class="mb-0">Please remove all your errors.</p>
                </div>
                <form class="row g-3" method="POST" id="uploadDocumentForm">
                    <div class="col-md-6">
                        <input type="text" id="firstName" name="first_name" class="form-control" placeholder="First name" aria-label="First name">
                    </div>
                    <div class="col-md-6">
                        <input type="text" id="lastName" name="last_name" class="form-control" placeholder="Last name" aria-label="Last name">
                    </div>
                    <div class="col-md-6">
                        <input type="number" id="age" name="age" class="form-control" placeholder="Age" aria-label="Age">
                    </div>
                    <div class="col-md-6">
                        <input type="number" id="phoneNo" name="phone_no" class="form-control" placeholder="Phone no" aria-label="Phone no">
                    </div>
                    <div class="input-group mb-3">
                        <input type="file" name="document" class="form-control" id="inputGroupFile02" accept=".png,.jpeg,.pdf">
                        <label class="input-group-text" for="inputGroupFile02"></label>
                    </div>
                    <div class="col-12">
                        <button type="submit" id="submitBtn" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="card mt-4">
            <div class="card-header">Document list</div>
            <div class="card-body">
                <ol class="list-group list-group-numbered">
                    <?php
                    foreach ($documents as $doc) {
                    ?>
                        <li class="list-group-item d-flex justify-content-between align-items-start">
                            <div class="ms-2 me-auto">
                                <div class="fw-bold">
                                    <?= $doc["first_name"] . " " . $doc["last_name"] ?>
                                    <span class="badge bg-primary rounded-pill"><?= $doc["age"] ?></span>
                                </div>
                                <?= $doc["phone_no"] ?>
                            </div>
                            <a href="<?= SHOW_DOCUMENT_PATH . $doc["document"] ?>" target="_blank" class="btn btn-primary">Document</a>
                        </li>
                    <?php } ?>
                </ol>
            </div>
        </div>
    </div>
    <script src="<?= BASE_URL ?>assets/js/jquery.min.js"></script>
    <script src="<?= BASE_URL ?>assets/js/bootstrap.min.js"></script>
    <script>
        $("#uploadDocumentForm").on("submit", function(e) {
            e.preventDefault();
            if ($("#firstName").val().lenght <= 0 && $("#lastName").val().lenght <= 0 && $("#age").val().lenght <= 0 && $("#phoneNo").val().lenght <= 0) {
                alert("Please enter correct data");
                return;
            }
            var form = $(this)[0];
            var formData = new FormData(form);
            formData.append("submit", "upload_document");
            $("#submitBtn").attr("disabled", true);
            $.ajax({
                url: "api/upload_document.php",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: (response) => {
                    $("#submitBtn").attr("disabled", false);
                    response = JSON.parse(response);
                    if (response.success) {
                        window.location.reload();
                    } else {
                        if ("data" in response) {
                            $("#error-block").show();
                            var errors = "";
                            for (var [key, msg] of Object.entries(response.data)) {
                                errors += "<li>" + msg + "</li>";
                            }
                            $("#error-list").html(errors);
                        } else alert(response.message);
                    }
                },
                complete: () => $(this).find(':input[type=submit]').removeClass('button--loading')
            });
        });
    </script>
</body>

</html>