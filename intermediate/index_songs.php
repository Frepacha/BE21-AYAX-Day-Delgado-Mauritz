<!DOCTYPE html>
<html>

<head>
    <title>Working with XML | intermediate</title>
</head>

<body>
    <h1>Working with XML | intermediate</h1>
    <button type="button" onclick="loadData()">Get my Songs collection</button>
    <br>
    <br>
    <div id="content">
        <!-- Here the content from myFunction() will be displayed -->
    </div>
    <script>
        function loadData() {
            var xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                if (this.status == 200) {
                    myFunction(this);
                }
            };
            xhttp.open("GET", "songs.xml", true);
            xhttp.send();
        }

        function myFunction(xml) {
            let xmlDoc = xml.responseXML;
            let x = xmlDoc.getElementsByTagName("songs"); //this will create an array with all albums --> WHAT we will display
            let content = document.getElementById("content"); //we save the div#content in a variable --> WHERE the data will be displayed
            for (let i = 0; i < x.length; i++) {
                content.innerHTML += //within the content div, we want to show the following:
                    "<h1>" +
                    x[i].getElementsByTagName("title")[0].childNodes[0].nodeValue +
                    "</h1>  " +
                    x[i].getElementsByTagName("artist")[0].childNodes[0].nodeValue +
                    " <br>" +
                    x[i].getElementsByTagName("country")[0].childNodes[0].nodeValue +
                    "<br>" +
                    x[i].getElementsByTagName("genre")[0].childNodes[0].nodeValue +
                    "<br>";
                //targeting the array of the tag name artist, accessing their child node and the value within that node
                //targeting the array of tag name title, accessing their child node and the value within that node
                //targeting the array of tag name description, accessing their child node and the value within that node
            }
        }
    </script>
</body>

</html>