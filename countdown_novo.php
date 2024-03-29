<?php

function global_countdown_novo($atts)
{
    $atts = shortcode_atts(
        array(
            'live' => '',
            'data' => '',
            'horario' => '',
            'cor' => '',
        ),
        $atts,
        'global_cadastra_form'
    );

    $timezone = 'America/Sao_Paulo';
    $dateString = DateTime::createFromFormat('d/m/Y H:i:s', $atts['data'] . ' ' . $atts['horario'] . ':00')->format('Y-m-d H:i:s');
    $dtUtcDate = strtotime($dateString . ' '. $timezone);
    
    $current_user = wp_get_current_user();
    $nome = $current_user->user_firstname . " " . $current_user->user_lastname;
    $cidade = get_user_meta($current_user->ID, "billing_city", true);
    $uf = get_user_meta($current_user->ID, "billing_state", true);
    $email = $current_user->user_email;
    $url = get_option('aovivo_global') . '/?live=' . $atts['live'] . '&nome=' . $nome .  '&cidade=' . $cidade .  '&uf=' . $uf .  '&email=' . $email;

    ob_start();
?>
    <style>
        .progress-container {
            display: flex;
            justify-content: space-between;
            width: 100%;
            max-width: 460px;
            margin: 0 auto;
            gap: 20px;
        }

        .progress-circle {
            width: 100px;
            height: 100px;
            transform: rotate(-90deg);
        }

        .progress-bar {
            fill: transparent;
            stroke-width: 4;
            stroke-dasharray: 314;
            stroke-dashoffset: 0;
            transition: stroke-dashoffset 0.5s ease-in-out;
            stroke: <?php echo $atts['cor']; ?>;
        }

        .progress-bar-bg {
            fill: transparent;
            stroke-width: 4;
            stroke-dashoffset: 3;
            stroke: #e7e7e7;
        }

        .progress-text {
            text-align: center;
            font-size: 18px;
        }

        .progress-text span {
            display: block;
            font-size: 13px;
        }

        #days-label,
        #hours-label,
        #minutes-label,
        #seconds-label {
            font-weight: 700;
            font-size: 30px;
            margin-top: -75px;
            margin-bottom: 30px;
        }
    </style>
    <div class="progress-container">
        <div>
            <svg class="progress-circle">
                <circle class="progress-bar-bg" cx="50px" cy="50px" r="45px"></circle>
                <circle class="progress-bar days" cx="50px" cy="50px" r="45px"></circle>
            </svg>
            <div class="progress-text">
                <span id="days-label">0</span>
                <span>dias</span>
            </div>
        </div>
        <div>
            <svg class="progress-circle">
                <circle class="progress-bar-bg" cx="50px" cy="50px" r="45px"></circle>
                <circle class="progress-bar hours" cx="50px" cy="50px" r="45px"></circle>
            </svg>
            <div class="progress-text">
                <span id="hours-label">0</span>
                <span>horas</span>
            </div>
        </div>
        <div>
            <svg class="progress-circle">
                <circle class="progress-bar-bg" cx="50px" cy="50px" r="45px"></circle>
                <circle class="progress-bar minutes" cx="50px" cy="50px" r="45px"></circle>
            </svg>
            <div class="progress-text">
                <span id="minutes-label">0</span>
                <span>minutos</span>
            </div>
        </div>
        <div>
            <svg class="progress-circle">
                <circle class="progress-bar-bg" cx="50px" cy="50px" r="45px"></circle>
                <circle class="progress-bar seconds" cx="50px" cy="50px" r="45px"></circle>
            </svg>
            <div class="progress-text">
                <span id="seconds-label">0</span>
                <span>segundos</span>
            </div>
        </div>
    </div>

    <script>
        function getCurrentDateFromServer() {
            var apiUrl = '<?= get_home_url() . '/wp-content/plugins/webmodera-plugin/diff-datas.php?data=' . $dtUtcDate; ?>';

            console.log(apiUrl)
            var xhr = new XMLHttpRequest();
            xhr.open('GET', apiUrl, false); // Make a synchronous request

            xhr.send();

            if (xhr.readyState === 4 && xhr.status === 200) {
                var response = JSON.parse(xhr.responseText);
                return response;
            } else {
                return null; // Handle errors as needed
            }
        }

        function updateCountdown() {

            var serverDiff = getCurrentDateFromServer();

            var daysServer = serverDiff.dias;
            var hoursServer = serverDiff.horas;
            var minutesServer = serverDiff.minutos;
            var secondsServer = serverDiff.segundos;
            var timeRemaining = serverDiff.passado;

            if (timeRemaining) {
                window.location.href = '<?= $url; ?>';
                document.getElementById("days-label").textContent = "0";
                document.getElementById("hours-label").textContent = "0";
                document.getElementById("minutes-label").textContent = "0";
                document.getElementById("seconds-label").textContent = "0";
                updateProgress(0, "days"); // Start with 0% progress
                updateProgress(0, "hours");
                updateProgress(0, "minutes");
                updateProgress(0, "seconds");
            } else {
                var days = daysServer;
                var hours = hoursServer;
                var minutes = minutesServer;
                var seconds = secondsServer;

                // Update progress bars and labels
                updateProgress((100 * days) / 30, "days"); // Assuming 30 days for demonstration
                updateProgress((100 * hours) / 24, "hours");
                updateProgress((100 * minutes) / 60, "minutes");
                updateProgress((100 * seconds) / 60, "seconds");

                document.getElementById("days-label").textContent = days;
                document.getElementById("hours-label").textContent = hours;
                document.getElementById("minutes-label").textContent = minutes;
                document.getElementById("seconds-label").textContent = seconds;

                setTimeout(updateCountdown, 1000); // Update every 1 second
            }
        }

        function updateProgress(percentage, unit) {
            const circle = document.querySelector(`.progress-bar.${unit}`);
            const circumference = 314;

            if (circle) {
                const offset = ((percentage / 100) * circumference) + 10;
                circle.style.strokeDashoffset = offset;
            }
        }

        updateCountdown();
    </script>
<?php
    return ob_get_clean();
}
add_shortcode('contador_novo', 'global_countdown_novo');
