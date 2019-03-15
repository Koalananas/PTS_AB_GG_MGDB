import React, {Component} from 'react';

class InputCase extends Component {

    constructor(props) {
      super(props);
      this.state = {
        start: 1,
        end: 5, 
        methode: 'BrutPower'
      };
  
      this.handleOriginChange = this.handleOriginChange.bind(this);
      this.handleDestinatioonChange = this.handleDestinatioonChange.bind(this);
      this.handleJourneyTypeChange = this.handleJourneyTypeChange.bind(this);
      this.handleSubmit = this.handleSubmit.bind(this);
    }
  
    handleOriginChange(event) {
      const target = event.target;
      const startInput = target.type === 'number' ? target.checked : target.value;
      const name = target.name;
  
      this.setState({
        [name]: startInput
      });
    }
    handleDestinatioonChange(event) {
      const target = event.target;
      const startInput = target.type === 'number' ? target.checked : target.value;
      const name = target.name;
  
      this.setState({
        [name]: startInput
      });
    }

    handleJourneyTypeChange(event) {
      this.setState({value: event.target.value});
    }

    handleSubmit(event) {
      alert('Your favorite flavor is: ' + this.state.value);
      event.preventDefault();
    }

    render() {
      return (
        <form>
          <label>
            Origine :  
            <input
              name="start"
              type="number"
              checked={this.state.isGoing}
              placeholder={'Chiffres entre 1 et 20'}
              onChange={this.handleOriginChange} />
          </label>
          <br />
          <label>
            Destination : 
            <input
              name="end"
              type="number"
              value={this.state.numberOfGuests}
              placeholder={'Chiffres entre 1 et 20'}
              onChange={this.handleDestinatioonChange} />
          </label>
          <br />
          <label>
            Type de trajet : 
            <select value={this.state.value} onChange={this.handleJourneyTypeChange}>
              <option value="BrutPower">Tous chemins</option>
              <option value="Dijkstra">Le plus rapide</option>
              <option value="FordFerkuson">Le plus court</option>
            </select>
          </label>
          <br />
          <input type="submit" value="Submit" />
        </form>
      );
    }
  }
  
  export default InputCase;
  