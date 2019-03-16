import React, { Component } from 'react';
import './Styles/App.css';
import InputCase from './Components/InputCase'


class App extends Component {
  constructor(props) {
    super(props);
    this.state = { 
  };
}

  render() {

    return (
      <div className="App">
        <header className="App-header">
          <InputCase/>
        </header>
      </div>
    );
  }
}

export default App;
