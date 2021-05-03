<!DOCTYPE html>
<html>

<body>
    <div id="content">
        <h1>The XMLHttpRequest Object</h1>
        <button type="button" onclick="loadDoc()">Change Content</button>
    </div>
    <script>
        function loadDoc() {
            let xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                if (this.status == 200) {
                    document.getElementById("content").innerHTML = this.responseText;
                }else{
                    document.getElementById("content").
                    innerHTML = "it dont work";
                }
            };
            xhttp.open("GET", "disc_doc.txt", true); //(method, URL, async)
            xhttp.send();
        }
    </script>
</body>

</html>