<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
    body {
        margin: 0;
        min-width: 250px;
    }

    /* Include the padding and border in an element's total width and height */
    * {
        box-sizing: border-box;
    }

    /* Remove margins and padding from the list */
    ul {
        margin: 0;
        padding: 0;
    }

    /* Style the list items */
    ul li {
        cursor: pointer;
        position: relative;
        padding: 12px 8px 12px 40px;
        list-style-type: none;
        background: #eee;
        font-size: 18px;
        transition: 0.2s;

        /* make the list items unselectable */
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    /* Set all odd list items to a different color (zebra-stripes) */
    ul li:nth-child(odd) {
        background: #f9f9f9;
    }

    /* Darker background-color on hover */
    ul li:hover {
        background: #ddd;
    }

    /* When clicked on, add a background color and strike out text */
    ul li.checked {
        background: #888;
        color: #fff;
        text-decoration: line-through;
    }

    /* Add a "checked" mark when clicked on */
    ul li.checked::before {
        content: '';
        position: absolute;
        border-color: #fff;
        border-style: solid;
        border-width: 0 2px 2px 0;
        top: 10px;
        left: 16px;
        transform: rotate(45deg);
        height: 15px;
        width: 7px;
    }

    /* Style the close button */
    .close {
        position: absolute;
        right: 0;
        top: 0;
        padding: 12px 16px 12px 16px;
    }

    .close:hover {
        background-color: #f44336;
        color: white;
    }

    /* Style the header */
    .header {
        background-color: #f44336;
        padding: 30px 40px;
        color: white;
        text-align: center;
    }

    /* Clear floats after the header */
    .header:after {
        content: "";
        display: table;
        clear: both;
    }

    /* Style the input */
    input {
        margin: 0;
        border: none;
        border-radius: 0;
        width: 75%;
        padding: 10px;
        float: left;
        font-size: 16px;
    }

    /* Style the "Add" button */
    .addBtn {
        padding: 10px;
        width: 25%;
        background: #d9d9d9;
        color: #555;
        float: left;
        text-align: center;
        font-size: 16px;
        cursor: pointer;
        transition: 0.3s;
        border-radius: 0;
    }

    .addBtn:hover {
        background-color: #bbb;
    }
    </style>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body>
    <?php
    $addToDoListRoute = route('todolist.save');
?>

    <div id="myDIV" class="header">
        <h2 style="margin:5px">My To Do List</h2>
        <button onclick="showAll()">Show All</button>
        <input type="text" id="myInput" placeholder="Title...">
        <span onclick="newElement('{{$addToDoListRoute}}')" class="addBtn">Add</span>
    </div>

    <ul id="myUL">
        @foreach($todolist as $key => $value)
        <li class="{{$value->status == 1 ? 'checked' : ''}}" value="{{$value->task_name}}"><input type="checkbox" style="width: 5%;" {{$value->status == 1 ? 'checked' : ''}}>{{$value->task_name}}</li>
        @endforeach
    </ul>
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js" type="text/javascript"></script>

    <script>
    // Create a "close" button and append it to each list item
    var myNodelist = document.getElementsByTagName("LI");
    var i;
    for (i = 0; i < myNodelist.length; i++) {
        var span = document.createElement("SPAN");
        var txt = document.createTextNode("\u00D7");
        span.className = "close";
        span.appendChild(txt);
        myNodelist[i].appendChild(span);
    }

    // Click on a close button to hide the current list item
    var close = document.getElementsByClassName("close");
    var i;
    var count = 0;
    for (i = 0; i < close.length; i++) {
        close[i].onclick = function() {
            alert("came")
            var div = this.parentElement;
            swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this data!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            url: "{{route('todolist.delete')}}",
                            type: "get", //send it through get method
                            data: {
                                myInput: this.parentElement.innerText.substring(0, this.parentElement
                                    .innerText.length - 1).trim()
                            },
                            success: function(response) {
                                //Do Something

                                div.style.display = "none";
                            },
                            error: function(xhr) {
                                //Do Something to handle error
                            }
                        });
                    } else {
                        swal("Your data is safe!");
                    }
                });
            console.log(this.parentElement.innerText.substring(0, this.parentElement.innerText.length - 1).trim());

            count++;
        }
    }

    // Add a "checked" symbol when clicking on a list item
    var list = document.querySelector('ul');
    list.addEventListener('click', function(ev) {
        console.log(ev.srcElement);
        if (count == 0) {
            $.ajax({
                url: "{{route('todolist.complete')}}",
                type: "get", //send it through get method
                data: {
                    myInput: ev.srcElement.innerText.substring(0, ev.srcElement.innerText.length - 1)
                        .trim()
                },
                success: function(response) {
                    //Do Something
                    if (ev.target.tagName === 'LI') {
                        ev.target.classList.toggle('checked');
                        if (response.data.updatedStatus == 1)
                            ev.srcElement.hidden = "true";
                        else
                            ev.srcElement.hidden = "false";
                    }
                },
                error: function(xhr) {
                    //Do Something to handle error
                }
            });
        }
    }, false);

    // Create a new list item when clicking on the "Add" button
    function newElement(url) {
        $.ajax({
            url: url,
            type: "get", //send it through get method
            data: {
                myInput: document.getElementById('myInput').value
            },
            success: function(response) {
                //Do Something
                if (response.data.status == 200) {
                    var li = document.createElement("li");
                    var inputValue = document.getElementById("myInput").value;
                    var t = document.createTextNode(inputValue);
                    li.appendChild(t);
                    if (inputValue === '') {
                        alert("You must write something!");
                    } else {
                        document.getElementById("myUL").appendChild(li);
                    }
                    document.getElementById("myInput").value = "";

                    var span = document.createElement("SPAN");
                    var txt = document.createTextNode("\u00D7");
                    span.className = "close";
                    span.appendChild(txt);
                    li.appendChild(span);

                    for (i = 0; i < close.length; i++) {
                        close[i].onclick = function() {
                            var div = this.parentElement;
                            div.style.display = "none";
                        }
                    }
                } else {
                    swal({
                        title: "Error",
                        text: response.data.message,
                        icon: "error",
                    });
                }
            },
            error: function(xhr) {
                //Do Something to handle error
            }
        });
    }


    function showAll() {
        $.ajax({
            url: "{{route('show.all')}}",
            type: "get", //send it through get method
            success: function(response) {
                //Do Something
                response.data.data.map(item => {
                    var li = document.createElement("li");
                    var inputValue = item.task_name;
                    var t = document.createTextNode(inputValue);
                    li.appendChild(t);
                    if (inputValue === '') {
                    } else {
                        document.getElementById("myUL").appendChild(li);
                    }
                    li.setAttribute("value", item.task_name);
                    document.getElementById("myInput").value = "";
                    var checkBox = document.createElement("INPUT");
                    checkBox.setAttribute("type", "checkbox");
                    checkBox.setAttribute("checked", true);
                    checkBox.setAttribute("style", "width: 5%");
                    var span = document.createElement("SPAN");
                    var txt = document.createTextNode("\u00D7");
                    span.className = "close";
                    span.appendChild(txt);
                    li.appendChild(checkBox);
                    li.appendChild(span);

                    for (i = 0; i < close.length; i++) {
                        close[i].onclick = function() {
                            var div = this.parentElement;
                            div.style.display = "none";
                        }
                    }
                });
            },
            error: function(xhr) {
                //Do Something to handle error
            }
        });
    }
    </script>

</body>

</html>