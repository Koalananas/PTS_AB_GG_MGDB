import React, {Component} from 'react';

class InputCase extends Component {

    constructor(props) {
      super(props);
      this.state = {
        start: '',
        end: '',
        methode: '',
        //isEnabled:  false
      };
  
      this.handleOriginChange = this.handleOriginChange.bind(this);
      this.handleDestinatioonChange = this.handleDestinatioonChange.bind(this);
      this.handleJourneyTypeChange = this.handleJourneyTypeChange.bind(this);
      this.handleSubmit = this.handleSubmit.bind(this);
      this.parseUrl  = this.parseUrl.bind(this);
      this.callMamp  = this.callMamp.bind(this);
    }

    componentDidMount(){

    }

    componentDidUpdate(prevProps, prevState) {
      if((this.state.start != prevState.start) || (this.state.end != prevState.end) || (this.state.methode != prevState.methode))
      {
        this.setState({
          
        })
      }
    }


    parseUrl(start,end, methode){
      let url = "http://localhost:8888/PTS_AB_GG_MGDB/Calcul/jsonResult.php?"
      if(start > 0 && start < 25) {
        url += 'start=' + start + '&';
      }
      if(end > 0 && end < 25) {
        url += 'end=' + end + '&';
      }
      if(methode !== null){
        if(methode === 'Dijkstra'){   
          url += 'methode=d';
        }
        else if(methode === 'FordFerkuson'){
          url += 'methode=f';      
        }
        else {
          url += 'methode=b';
        }
      }
      const jsonResult = this.callMamp(url);
      console.log('url',url);
      return jsonResult;
    }

    callMamp(url) {
      var xhttp = new XMLHttpRequest();
      xhttp.open("GET", url, false);
      xhttp.send();
      const http = xhttp;
      console.log('xhttp',http);

  
      var obj = xhttp.responseText;
      
      return obj;
    }
  
    handleOriginChange(event) {
      const startInput = event.target.value;
  
      this.setState({
        start: startInput
      });
    }
    handleDestinatioonChange(event) {
      const startInput = event.target.value;
  
      this.setState({
        end: startInput
      });
    }

    handleJourneyTypeChange(event) {
      this.setState({methode: event.target.value});
    }

    handleSubmit(event) {
      const {start, end, methode} = this.state;
      const json = this.parseUrl(start,end,methode);
      console.log(json);
    }

    render() {
      
      const {start, end, methode} = this.state;
      console.log(this.state)
      const json = this.parseUrl(start,end,methode);
      console.log(json);

      return (
        <form>
          <label>
            Origine :  
            <input
              name='start'
              type='number'
              value={start}
              placeholder={'Chiffres entre 1 et 20'}
              onChange={this.handleOriginChange} />
          </label>
          <br />
          <label>
            Destination : 
            <input
              name='end'
              type='number'
              value={end}
              placeholder={'Chiffres entre 1 et 20'}
              onChange={this.handleDestinatioonChange} />
          </label>
          <br />
          <label>
            Type de trajet : 
            <select value={methode} onChange={this.handleJourneyTypeChange}>
              <option value='BrutPower'>Tous chemins</option>
              <option value='Dijkstra'>Le plus rapide</option>
              <option value='FordFerkuson'>Le plus court</option>
            </select>
          </label>
          <br />
          <input type="submit" value="Submit" onSubmit={this.handleSubmit}/>
        </form>
      );
    }
  }
  
  export default InputCase;
  