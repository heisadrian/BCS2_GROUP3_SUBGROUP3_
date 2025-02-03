<?php
include "./php/db.php";

$error = "";
$message = "";

if (
    $_SERVER['REQUEST_METHOD'] == 'POST' &&
    isset($_POST['email']) &&
    isset($_POST['fname']) &&
    isset($_POST['lname']) &&
    isset($_POST['password']) &&
    isset($_POST['cpassword'])
) {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $cpassword = $_POST['cpassword'];
    $check = $_POST['check'] ?? "";
    $username = $fname . substr(uniqid(), 5) . $lname;

    if (!$check) {
        $error = "Please agree to our terms of use!";
    } elseif ($password !== $cpassword) {
        $error = "Password mismatch!";
    } else {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $error = "Please enter a valid Email!";
        } else {
            $sql = "SELECT user_id FROM users WHERE email = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $error = "Email already exists! Please try another!";
            } else {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $uniqid = substr(uniqid(), 7) . "-" . substr(uniqid(), 12) . substr(uniqid(), 8) . "-" . substr(uniqid(), 7);

                $sql = "INSERT INTO users (user_id, username, fname, lname, email, password)
                        VALUES (?, ?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssss", $uniqid, $username, $fname, $lname, $email, $hashedPassword);

                if ($stmt->execute() && !$error) {
                    $message = "Registered Successfully";
                    header('Location: index.php');
                    exit;
                } else {
                    $error = "Error in inserting user to the database!";
                }
            }

            $stmt->close();
        }
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- Favicon -->
  <link href="assets/images/favicon.png" rel="icon" type="image/png">

  <!-- title and description-->
  <title>SOCIALITE | REGISTRATION</title>
  <meta name="description" content="Socialite - Social sharing network HTML Template">

  <!-- css files -->
  <link rel="stylesheet" href="assets/css/tailwind.css">
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/custom.css">

  <!-- google font -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600;700;800&display=swap"
    rel="stylesheet">

</head>

<body>

  <div class="sm:flex">

    <div
      class="relative lg:w-[580px] md:w-96 w-full p-10 min-h-screen bg-white shadow-xl flex items-center pt-10 dark:bg-slate-900 z-10">

      <div class="w-full lg:max-w-sm mx-auto space-y-10"
        uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">

        <!-- logo image-->
        <a href="#"> <img src="assets/images/logo.png" class="w-28 absolute top-10 left-10" alt=""></a>
        <!-- <a href="#"> <img src="assets/images/logo-light.png" class="w-28 absolute top-10 left-10 hidden dark:!block"
            alt=""></a> -->

        <!-- logo icon optional -->
        <div class="hidden">
          <img class="w-12" src="assets/images/logo.png" alt="Socialite html template">
        </div>

        <!-- title -->
        <div>
          <h2 class="text-2xl font-semibold mb-1.5"> Sign up to get started </h2>
          <p class="text-sm text-gray-700 font-normal">If you already have an account, <a href="index.php"
              class="text-blue-700">Login here!</a></p>
        </div>


        <!-- form -->
        <form method="POST"
          action="<?php echo $_SERVER['PHP_SELF']; ?>"
          class="space-y-7 text-sm text-black font-medium 
          dark:text-white" uk-scrollspy="target: > *; 
          cls: uk-animation-scale-up; delay: 100 ;repeat: true">

          <div class="grid grid-cols-2 gap-4 gap-y-7">

            <!-- first name -->
            <div>
              <label for="Fname" class="">First name</label>
              <div class="mt-2.5">
                <input id="fname" name="fname" type="text"
                  autofocus="" placeholder="First name" required=""
                  class="!w-full !rounded-lg !bg-transparent 
                  !shadow-sm !border-slate-200 dark:!border-slate-800 
                  dark:!bg-white/5">
              </div>
            </div>

            <!-- Last name -->
            <div>
              <label for="Lname" class="">Last name</label>
              <div class="mt-2.5">
                <input id="lname" name="lname" type="text"
                  placeholder="Last name" required=""
                  class="!w-full !rounded-lg !bg-transparent 
                  !shadow-sm !border-slate-200 dark:!border-slate-800 
                  dark:!bg-white/5">
              </div>
            </div>

            <!-- email -->
            <div class="col-span-2">
              <label for="email" class="">Email address</label>
              <div class="mt-2.5">
                <input id="email" name="email" type="email"
                  placeholder="Email" required=""
                  class="!w-full !rounded-lg !bg-transparent 
                  !shadow-sm !border-slate-200 dark:!border-slate-800
                   dark:!bg-white/5">
              </div>
            </div>

            <!-- password -->
            <div>
              <label for="email" class="">Password</label>
              <div class="mt-2.5">
                <input id="password" name="password" type="password"
                  placeholder="*****" class="!w-full !rounded-lg 
                  !bg-transparent !shadow-sm !border-slate-200 
                  dark:!border-slate-800 dark:!bg-white/5">
              </div>
            </div>

            <!-- Confirm Password -->
            <div>
              <label for="email" class="">Confirm Password</label>
              <div class="mt-2.5">
                <input id="cpassword" name="cpassword" type="password"
                  placeholder="*****"
                  class="!w-full !rounded-lg !bg-transparent !shadow-sm 
                  !border-slate-200 dark:!border-slate-800 dark:!bg-white/5">
              </div>
            </div>

            <div class="col-span-2">
              <label class="inline-flex items-center" id="rememberme">
                <input type="checkbox" name="check" id="accept-terms" class="!rounded-md
                 accent-red-800" />
                <span class="ml-2">
                  you agree to our
                  <a href="#" class="text-blue-700 hover:underline">
                    terms of use
                  </a>
                </span>
              </label>

            </div>

            <div class="col-span-2">
                <?php if ($error) { ?>
                  <h2 class="font-semibold mb-1.5 error block">
                    <?php echo $error; ?>
                  </h2>
                <?php } ?>
           </div>

            <?php if ($message) { ?>
              <h2 class="font-semibold mb-1.5 message">
                <?php echo $message; ?>
              </h2>
            <?php } ?>
            <!-- submit button -->
            <div class="col-span-2">
              <button type="submit" name="submit"
                class="button bg-primary text-white w-full">
                Get Started
              </button>
            </div>
          </div>

        </form>

      </div>

    </div>

    <!-- image slider -->
    <div class="flex-1 relative bg-primary max-md:hidden">


      <div class="relative w-full h-full" tabindex="-1" uk-slideshow="animation: slide; autoplay: true">

        <ul class="uk-slideshow-items w-full h-full">
          <li class="w-full">
            <img src="assets/images/post/img-3.jpg" alt=""
              class="w-full h-full object-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left">
            <div class="absolute bottom-0 w-full uk-tr ansition-slide-bottom-small z-10">
              <div class="max-w-xl w-full mx-auto pb-32 px-5 z-30 relative"
                uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">
                <img class="w-12" src="assets/images/logo-icon.png" alt="Socialite html template">
                <h4 class="!text-white text-2xl font-semibold mt-7" uk-slideshow-parallax="y: 600,0,0"> Connect With
                  Friends </h4>
                <p class="!text-white text-lg mt-7 leading-8" uk-slideshow-parallax="y: 800,0,0;"> This phrase is more
                  casual and playful. It suggests that you are keeping your friends updated on what’s happening in your
                  life.</p>
              </div>
            </div>
            <div class="w-full h-96 bg-gradient-to-t from-black absolute bottom-0 left-0"></div>
          </li>
          <li class="w-full">
            <img src="assets/images/post/img-2.jpg" alt=""
              class="w-full h-full object-cover uk-animation-kenburns uk-animation-reverse uk-transform-origin-center-left">
            <div class="absolute bottom-0 w-full uk-tr ansition-slide-bottom-small z-10">
              <div class="max-w-xl w-full mx-auto pb-32 px-5 z-30 relative"
                uk-scrollspy="target: > *; cls: uk-animation-scale-up; delay: 100 ;repeat: true">
                <img class="w-12" src="assets/images/logo-icon.png" alt="Socialite html template">
                <h4 class="!text-white text-2xl font-semibold mt-7" uk-slideshow-parallax="y: 800,0,0"> Connect With
                  Friends </h4>
                <p class="!text-white text-lg mt-7 leading-8" uk-slideshow-parallax="y: 800,0,0;"> This phrase is more
                  casual and playful. It suggests that you are keeping your friends updated on what’s happening in your
                  life.</p>
              </div>
            </div>
            <div class="w-full h-96 bg-gradient-to-t from-black absolute bottom-0 left-0"></div>
          </li>
        </ul>

        <!-- slide nav -->
        <div class="flex justify-center">
          <ul class="inline-flex flex-wrap justify-center  absolute bottom-8 gap-1.5 uk-dotnav uk-slideshow-nav"> </ul>
        </div>


      </div>


    </div>

  </div>


  <!-- Uikit js you can use cdn  https://getuikit.com/docs/installation  or fine the latest  https://getuikit.com/docs/installation -->
  <script src="assets/js/uikit.min.js"></script>
  <script src="assets/js/script.js"></script>

  <!-- Ion icon -->
  <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
  <script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>

  <!-- Dark mode -->
  <script>
    // On page load or when changing themes, best to add inline in `head` to avoid FOUC
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark')
    } else {
      document.documentElement.classList.remove('dark')
    }

    // Whenever the user explicitly chooses light mode
    localStorage.theme = 'light'

    // Whenever the user explicitly chooses dark mode
    localStorage.theme = 'dark'

    // Whenever the user explicitly chooses to respect the OS preference
    localStorage.removeItem('theme')
  </script>

</body>

</html>