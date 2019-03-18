import React, { Component } from 'react';
import './Styles/App.css';
import CardContainer from './Components/CardContainer';


class App extends Component {
  constructor(props) {
    super(props);
    this.state = { 
  };
}

  render() {

    return (
      <div >
        <CardContainer/>
      </div>
    );
  }
}

export default App;
