<style> #loader {position: fixed;left: 0;top: 0;width: 100%;height: 100%;z-index: 1000;background: rgba(255, 255, 255, 0.8);display: flex;justify-content: center;align-items: center;flex-direction:column;} #loader img {width: 100px;height: auto;margin-bottom:10px;} #loading-message span {font-size:14px;color:#000;}</style>
<div id="loader"><img src="../img/ccs.gif" alt="Loading..."><div id="loading-message"><span>Loading...</span></div></div>

<script> window.addEventListener('load', () => { const loader = document.getElementById('loader'); if (loader) { loader.style.display = 'none'; } }); </script>
