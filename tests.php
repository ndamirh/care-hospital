<?php
// Start the session if not already started
session_start();

// Database connection
 // Database connection details
    $servername = "localhost";
    $username = "root"; // Adjust if needed
    $password = ""; // Adjust if needed
    $dbname = "hospitals"; // Adjust if needed

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch symptoms from the database
$symptomsOptions = "";
$sql = "SELECT * FROM organ_symptoms_diagnoses";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $symptomsOptions .= '<option value="' . htmlspecialchars($row['id']) . '">' . htmlspecialchars($row['symptoms']) . '</option>';
    }
}

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment</title>
    <link rel="stylesheet" href="appoinments.css">
    <link rel="icon" href="img/favicon.ico">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        body {
            font-family: 'Roboto', sans-serif;
        }
        .diagnosis-result {
            margin-top: 20px;
        }
        .diagnosis-result img {
            max-width: 100px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <header>
        <div class="header-container">
            <a href="mainpage.html">
                <img src="img/logos.png" width="50" height="50" alt="Hospital Pantai Logo">
            </a>
            <div class="header-text">
                <p class="main-title">CARE HOSPITALS</p>
                <p class="sub-title">Caring From the Heart</p>
            </div>
            <nav class="nav-links">
                <a href="tel:+6074338811"><img src="img/cs.png" alt="Customer Service" width="24" height="24"> +607 433 8811</a>
                <a href="tel:+6074339991"><img src="img/ambulance.png" alt="Ambulance" width="24" height="24"> +607 433 9991</a>
                <a href="findadoctor.php">Find a Doctor</a>
                <div class="dropdown">
                    <a href="#">Patient & Visitors</a>
                    <div class="dropdown-content">
                        <a href="appoinment.php">Appointments</a>
                        <a href="try wards.php">Ward Rates</a>
                    </div>
                </div>
                <a href="mainwebsite.html">Login</a>
            </nav>
        </div>
    </header>

    <div class="container">
        <div class="header text-center">
            <h2>Appointment</h2>
            <p>Before making an appointment, choose your symptoms.</p>
        </div>

        <div class="bg-light text-center rounded p-5">
            <h1>Book An Appointment</h1>
            <form id="symptomForm">
                <div class="row g-3">
                    <div class="col-12">
                        <label for="symptoms" class="form-label">Select Symptoms:</label>
                        <select name="symptoms[]" id="symptoms" class="form-select" multiple required>
                            <?php echo $symptomsOptions; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary w-100 py-3" type="button" onclick="showDiagnosis()">Show Diagnosis</button>
                    </div>
                </div>
            </form>

            <div class="diagnosis-result" id="diagnosisResult"></div>
        </div>
    </div>

    <script>
        function showDiagnosis() {
            const symptomsSelect = document.getElementById("symptoms");
            const selectedValues = Array.from(symptomsSelect.selectedOptions).map(option => option.value);
            const resultDiv = document.getElementById("diagnosisResult");

            // Clear previous results
            resultDiv.innerHTML = "";

            if (selectedValues.length === 0) {
                resultDiv.innerHTML = "<p>Please select at least one symptom.</p>";
                return;
            }

            // Fetch data from the server (same file)
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true); // Send request to the same file
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onload = function () {
                if (xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.length > 0) {
                        response.forEach(item => {
                            resultDiv.innerHTML += `
                                <div>
                                    <h4>${item.organ_name}</h4>
                                    <p><strong>Diagnosis:</strong> ${item.diagnoses}</p>
                                    <img src="${item.img}" alt="${item.organ_name}">
                                </div>
                            `;
                        });
                    } else {
                        resultDiv.innerHTML = "<p>No diagnosis found for the selected symptoms.</p>";
                    }
                } else {
                    resultDiv.innerHTML = "<p>Error occurred while fetching diagnosis.</p>";
                }
            };
            xhr.onerror = function () {
                resultDiv.innerHTML = "<p>Request failed.</p>";
            };
            xhr.send("symptoms=" + JSON.stringify(selectedValues));
        }
    </script>

    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
// Handle the AJAX request to get diagnoses
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if symptoms are set
    if (isset($_POST['symptoms'])) {
        $selectedSymptoms = json_decode($_POST['symptoms']);
        $diagnoses = [];

        // Reconnect to the database
        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare a statement to avoid SQL injection
        $stmt = $conn->prepare("SELECT organ_name, symptoms, diagnoses, img FROM organ_symptoms_diagnoses WHERE id IN (" . implode(',', array_fill(0, count($selectedSymptoms), '?')) . ")");
        $stmt->bind_param(str_repeat('i', count($selectedSymptoms)), ...$selectedSymptoms);
        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $diagnoses[] = $row;
        }

        $stmt->close();
        $conn->close();

        // Return the diagnoses as a JSON response
        echo json_encode($diagnoses);
    } else {
        echo json_encode([]); // No symptoms selected
    }
    exit; // Stop further execution
}
?>