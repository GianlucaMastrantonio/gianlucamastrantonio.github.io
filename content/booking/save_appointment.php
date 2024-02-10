<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") { // Check if the form is submitted
  $selectedSlots = $_POST['time-slot']; // Get the selected time slots from the form
  $email = $_POST['email']; // Get the student's email from the form

  if (!empty($selectedSlots)) { // Check if there are selected time slots
    $file = fopen("appointments.txt", "a"); // Open the file in append mode
    
    foreach ($selectedSlots as $slot) {
      fwrite($file, $slot . "\n"); // Write each selected time slot to a new line in the file
    }
    
    fclose($file); // Close the file
    
    // Send confirmation email
    $to = $email;
    $subject = "Appointment Confirmation";
    $message = "Your appointment has been booked for the following time slot(s):\n" . implode("\n", $selectedSlots);
    $headers = "From: your_email@example.com"; // Replace with your email address or a noreply address

    mail($to, $subject, $message, $headers); // Send the email

    echo "Appointments saved successfully. Confirmation email sent.";
  } else {
    echo "No time slots selected.";
  }
}
?>

