import React, { Component } from 'react';s
import './Styles/App.css';
import InputCase from './Components/InputCase'


export default class App extends Component {
  constructor(props) {
    super(props);
    this.state = { 
  };
    this.callMamp = this.callMamp.bind(this);
}

callMamp(url) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Typical action to be performed when the document is ready:
          document.getElementById("demo").innerHTML = xhttp.responseText;
        }
    };
    xhttp.open("GET", url, true);
    xhttp.send();
  
    return <div>xhttp</div>;
  } 

  render() {
    return (
      <div className="App">
        <header className="App-header">
          <InputCase/>
          {this.callMamp("http://localhost")}     
        </header>

      </div>
    );
  }
}

export default App;
