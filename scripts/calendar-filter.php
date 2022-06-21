<script>
    var elements = {
        filter: document.querySelector("#filter"),
        clear: document.querySelector("#clear"),
    };

    elements.filter.addEventListener("keyup", function() {
        var query = this.value;
        dp.events.filter(query); // see dp.onEventFilter below
    });

    elements.clear.addEventListener("click", function(ev) {
        ev.preventDefault();
        elements.filter.value = "";
        dp.events.filter(null);
    });
</script>