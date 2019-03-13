import React, {Component} from 'react';

class InputCase extends Component {

    constructor(props) {
      super(props);
      this.state = {
        start: 1,
        end: 5
      };
  
      this.handleInputChange = this.handleInputChange.bind(this);
    }
  
    handleInputChange(event) {
      const target = event.target;
      const startInput = target.type === 'number' ? target.checked : target.value;
      const name = target.name;
  
      this.setState({
        [name]: startInput
      });
    }

    render() {
      return (
        <form>
          <label>
            Is going:
            <input
              name="start"
              type="number"
              checked={this.state.isGoing}
              onChange={this.handleInputChange} />
          </label>
          <br />
          <label>
            Number of guests:
            <input
              name="end"
              type="number"
              value={this.state.numberOfGuests}
              onChange={this.handleInputChange} />
          </label>
        </form>
      );
    }
  }
  
  export default InputCase;
  