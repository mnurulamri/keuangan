<script>
window.onclick = function(event) {
    var modal = document.getElementById('myModal');
    if (event.target == modal) {
        modal.style.display = "none";
    }
} 
</script>

<style>
/* Modal Background */
.custom-modal {
    display: none; 
    position: fixed; 
    z-index: 1050; 
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    overflow: auto;
    background-color: rgba(0,0,0,0.5);
}

/* Modal Content */
.custom-modal-content {
    background-color: #fefefe;
    margin: 10% auto;
    padding: 20px;
    border: 1px solid #888;
    width: 98%;
    border-radius: 5px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Close Button */
.close-modal {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}

.close-modal:hover { color: black; }
</style>