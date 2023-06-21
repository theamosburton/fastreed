function dragStartHandler(event) {
    event.dataTransfer.setData("text/plain", event.target.id);
  }
  
  function dragOverHandler(event) {
    event.preventDefault();
  }
  
  function dropHandler(event) {
    event.preventDefault();
    const data = event.dataTransfer.getData("text/plain");
    const draggedElement = document.getElementById(data);
    const img = draggedElement.querySelector("img");
    event.target.innerHTML = "";
    event.target.appendChild(img);
  }
  