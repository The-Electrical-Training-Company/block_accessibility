console.clear();
var apikey = '';



var source = document.getElementById("page-content");
var oldText = source.innerHTML;
var cleanSource = source.innerHTML.replaceAll("<br>", "<br />");
source = cleanSource;

const btn = document.querySelector('#bionic-mode');



  document.getElementById("rangeFixation").oninput = function() {
     fixvalue();
 };
function fixvalue(){
  var fixation = '3';
var fixation = document.getElementById("rangeFixation").value;
console.log(fixation)
}



 document.getElementById("rangeSaccade").oninput = function() {
    fixsaccade()
  };

function initAPIkey(Y, apikeyconfig){
  apikey = apikeyconfig;
}

function fixsaccade(){
var saccade = document.getElementById("rangeSaccade").value;
console.log(saccade)
}

const toggleBionicMode = e => {
  e.target.classList.toggle('active');
  if (e.target.classList.contains('active')) {

        var buttonclick = document.getElementById("bionic-mode");
        buttonclick.innerText = "This might take a moment.";
        var source = document.getElementById("region-main");

        var fixation = document.getElementById("rangeFixation").value;
        console.log(fixation)
        var saccade = document.getElementById("rangeSaccade").value;
console.log(saccade)






        var endpoint = "https://bionic-reading1.p.rapidapi.com";
let mode = "html"; // the data format I want to get back
let textContent = source.innerHTML; // the text I want to convert
const containerElement = "#region-main"; // the element I want to put the html in

getBionicData();



function getBionicData() {

const encodedParams = new URLSearchParams();
encodedParams.append("content", textContent,);
encodedParams.append("response_type", "html");
encodedParams.append("request_type", "html");
encodedParams.append("fixation", fixation,);
encodedParams.append("saccade", saccade,);

const options = {
  method: "POST", // *GET, POST, PUT, DELETE, etc.: but we will use POST because we want to throw data to this API
  headers: {
    "content-type": "application/x-www-form-urlencoded",
    "X-RapidAPI-Host": "bionic-reading1.p.rapidapi.com", // API host
    "X-RapidAPI-Key": apikey // my personal API key
  },
  body: encodedParams
};
  let apiLink = `${endpoint}/convert`; // the assembled link to the API (usually more complex)

  fetch(apiLink, options) // fetching data from the API with the options above
    .then((res) => res.text()) // converting the data to text (html)
    .then((data) => cleanData(data)) // cleaning the data
    .then((data) => makeHTML(data)) // making the HTML
    .then((data) => renderHTML(containerElement, data)) // rendering the HTML in the DOM
    .catch((err) => console.error(err)); // logging any errors
}

function cleanData(data) {
  return data; // clean data once known what the res contajns

}

function makeHTML(data) {
  return `<p>${data} </p>`;  // assemble HTML from the data
}





function renderHTML(element, data) {
  var data = data.replaceAll("<br>", " ");
  document.querySelector(element).innerHTML = data; // get the element I want to put the html in, and fill it with the data
  var buttonclick = document.getElementById("bionic-mode");
        buttonclick.innerText = "Return to Default Text";
        console.log(fixation)
        console.log(saccade)
}




function getValue(element) {
  return document.querySelector(element).value; // get the value of the element
}

} else {
   var source = document.getElementById("page-content");
    source.innerHTML = oldText;
    var buttonclick = document.getElementById("bionic-mode");
        buttonclick.innerText = "Toggle Bionic Mode";
        console.clear();

    }


}


btn.addEventListener('click', toggleBionicMode);