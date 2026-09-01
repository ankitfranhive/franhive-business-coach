<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?></title>
    <link rel="shortcut icon" type="image/png" href="https://www.franhive.com/images/favicon.ico">
    
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }

.alert-success {
    color: #333333;
    background-color: #F3F6F8;
    border-color: #c3e6cb;
}
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        .my-header {
            background-color: #f9af30;
            color: #fff;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
        }
        .content {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .main-content, .side-content {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .main-content {
            flex: 2;
        }
        .side-content {
            flex: 1;
            overflow-y: auto;
            max-height: 500px;
        }
        .contact-form {
            background-color: #fff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
        }
        .contact-form input, .contact-form textarea {
            border-radius: 5px;
        }
        .contact-form button {
            background-color: #f9af30;
            border: none;
            padding: 10px 15px;
            border-radius: 5px;
            color: white;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
        }
        .contact-form button:hover {
            background-color: #e89c28;
        }
        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="my-header">
            <h1><?= htmlspecialchars($landing_page['LANDING_PAGE_NAME']); ?></h1>
        </div>
        <?php if (!empty($landing_page['BANNER_IMAGE'])): ?>
        <div class="banner text-center">
            <img src="<?= htmlspecialchars($landing_page['BANNER_IMAGE']) ?>" alt="Banner Image" class="img-fluid">
        </div>
        <?php endif; ?>
        <div class="content">
            <div class="main-content">
                <?= $landing_page['LANDING_PAGE_LEFT_CONTENT']; ?>
            </div>
            <div class="side-content">
                <?php if (!empty($landing_page['TESTIMONIALS_IMAGES'])): ?>
                    <h2 class="text-center text-brandcolor">Testimonials</h2>
                    <div class="testimonials-list">
                        <?php foreach (explode(',', $landing_page['TESTIMONIALS_IMAGES']) as $image): ?>
                            <div class="testimonial-image">
                                <img src="<?= base_url().htmlspecialchars(trim($image)) ?>" class="img-fluid w-100" alt="Testimonial Image">
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                <?= $landing_page['LANDING_PAGE_RIGHT_CONTENT']; ?>
            </div>
        </div>
        <div class="contact-form">
           
            <div id="contact-status"></div>
            <form id="contactForm">
            <h2>Register here</h2>

            <!-- Hidden field for EMAIL_TEMPLATE from database -->
            <input type="hidden" name="EMAIL_TEMPLATE" value="<?= isset($landing_page['EMAIL_TEMPLATE']) ? htmlspecialchars($landing_page['EMAIL_TEMPLATE']) : '' ?>">
            <!-- Debug: Email Template ID = <?= isset($landing_page['EMAIL_TEMPLATE']) ? $landing_page['EMAIL_TEMPLATE'] : 'NOT SET' ?> -->
            
                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="mobile">Mobile</label>
                    <input type="text" class="form-control" id="mobile" name="mobile" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <!-- <div class="form-group">
                    <label for="message">Message</label>
                    <textarea class="form-control" id="message" name="message" rows="3" required></textarea>
                </div> -->
                <div class="form-group">
                    <label for="platform">Which platform do you prefer to stay in touch?</label>
                    <select class="form-control" id="platform" name="platform" required>
                        <option value="" disabled selected>Select an option</option>
                        <option value="1">WhatsApp</option>
                        <option value="2">Messenger</option>
                        <option value="3">Email</option>
                    </select>
                </div>

                <?php if ($landing_page['LANDING_PAGE_ID'] == 20) { ?>
                    <button type="submit" class="btn btn-custom">YES! I Want to Activate My Relationships</button>
                <?php } else { ?>
                    <button type="submit" class="btn btn-custom">Submit</button>
                <?php } ?>

              
            </form>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>



    <!-- Adjust Side Content Height Script -->
    <script>
      function adjustSideContentHeight() {
    let mainContent = document.querySelector('.main-content');
    let sideContent = document.querySelector('.side-content');

    if (mainContent && sideContent) {
        let mainHeight = mainContent.offsetHeight;
        let sideHeight = sideContent.scrollHeight;

        if (sideHeight > mainHeight) {
            sideContent.style.maxHeight = mainHeight + "px"; // Restrict side-content height
            sideContent.style.overflowY = "auto"; // Enable scrolling
        } else {
            sideContent.style.maxHeight = "none"; // Remove max-height restriction
            sideContent.style.overflowY = "visible"; // No scroll needed
        }
    }
}

// Call function when page loads and on resize
window.addEventListener('load', adjustSideContentHeight);
window.addEventListener('resize', adjustSideContentHeight);

    </script>

    <!-- Contact Form Submission -->
    <script>
        $(document).ready(function() {
            $("#contactForm").submit(function(e) {
                e.preventDefault();
                
                $.ajax({
                    url: "<?= base_url('LandingPageController/submit_contact') ?>",
                    type: "POST",
                    data: $(this).serialize(),
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            $("#contact-status").html('<div class="alert alert-success">' + <?= json_encode($landing_page['LANDING_PAGE_THANKS_CONTENT']); ?> + '</div>');
$("#contactForm").hide();


                        } else {
                            $("#contact-status").html('<div class="alert alert-danger">'+response.message+'</div>');
                        }
                    },
                    error: function() {
                        $("#contact-status").html('<div class="alert alert-danger">An error occurred. Please try again.</div>');
                    }
                });
            });
        });
    </script>
</body>
</html>
