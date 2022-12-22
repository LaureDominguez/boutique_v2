let selectCategory = document.querySelector("#checkCategory");
let selectUrl = document.querySelector("#urlAddress").value;

selectCategory.addEventListener("change", (event) => {
    console.log(event.target.value)
    window.location.href = selectUrl + "/" + event.target.value
    
})