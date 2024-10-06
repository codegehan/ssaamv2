
</div>
<footer class="fixed-bottom bg-light text-center shadow">
    <p class="mb-0">Powered by: Creatives Committee ~ v<?=$_ENV['VERSION']?></p>
</footer>
<?php include('../lib/loader.php');?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
<!-- <style>
body { opacity: 0; visibility: hidden; transition: opacity 0.3s ease-in; }
.fade-in { opacity: 1; visibility: visible; }
.fade-out { opacity: 0;}
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const body = document.querySelector('body');
        
        // Set the body to visible but transparent initially
        body.style.visibility = 'visible'; // Make the body visible but with opacity 0

        fetchData().then(() => {
            // Once data is fetched, show the body with fade-in effect
            body.classList.add('fade-in');
        });

        document.querySelectorAll('a').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                body.classList.add('fade-out');
                const href = this.href;
                setTimeout(function() {
                    window.location.href = href;
                }, 500); // Match this timeout with the fade-out duration
            });
        });
    });

    // Simulate fetching data (replace this with your actual fetch call)
    function fetchData() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve(); // Simulate an asynchronous fetch operation
            }, 300); // Simulate 2 seconds of data fetching
        });
    }
</script> -->
</body>
</html>