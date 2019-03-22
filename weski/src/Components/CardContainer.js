import React, {Component} from 'react';
import Card from './Card';
import Checkbox from './Checkbox';
import './../Styles/App.css';

const checkboxes = [
  {
    name: 'Tous',
    key: 'Tous',
    label: 'Tous',
  },
  {
    name: 'Vertes',
    key: 'Vertes',
    label: 'Vertes',
  },
  {
    name: 'Bleus',
    key: 'Bleus',
    label: 'Bleus',
  },
  {
    name: 'Rouges',
    key: 'Rouges',
    label: 'Rouges',
  },
  {
    name: 'Noires',
    key: 'Noires',
    label: 'Noires',
  },
];

class CardContainer extends Component {

    constructor(props) {
      super(props);
      this.state = {
        onSubmit: false,
        start: '',
        end: '',
        methode: 'BrutForce',
        data:{
            0:{
              ways:  [],
              totalMinuteTime:'',
            },
            query:{
              start: '',
              end:'',
              methode:'',
            },
        },
        //isEnabled:  false
        checkedItems: new Map(),
      };
  
      this.handleOriginChange = this.handleOriginChange.bind(this);
      this.handleDestinationChange = this.handleDestinationChange.bind(this);
      this.handleJourneyTypeChange = this.handleJourneyTypeChange.bind(this);
      this.handleSubmit = this.handleSubmit.bind(this);
      this.parseUrl  = this.parseUrl.bind(this);
      this.callMamp  = this.callMamp.bind(this);
      this.buildUrl = this.buildUrl.bind(this);
      this.cards = this.cards.bind(this);
      this.handleChangeCheckbox =this.handleChangeCheckbox.bind(this);
    }

    componentDidMount(){
      this.selectedCheckboxes = new Set();
    }

    componentDidUpdate(prevProps, prevState) {


    }

    toggleCheckbox = label => {
      if (this.selectedCheckboxes.has(label)) {
        this.selectedCheckboxes.delete(label);
      } else {
        this.selectedCheckboxes.add(label);
      }
    }

    buildUrl(start,end, methode){
      let url = "http://localhost:8888/PTS_AB_GG_MGDB/jsonResult.php?"
      //let checks = checkboxes.map(item => item = this.state.checkedItems.get(item));
      //console.log(checks)
      if(start > 0 && start < 96) {
        url += 'start=' + start + '&';
      }
      if(end > 0 && end < 96) {
        url += 'end=' + end + '&';
      }
      if(methode !== null){
        if(methode === 'Dijkstra'){   
          url += 'methode=d';
        }
        else if(methode === 'FordFulkerson'){
          url += 'methode=f';      
        }
        else {
          url += 'methode=b';
        }
      }
      return url;
    }

    callMamp(url) {
      var xhttp = new XMLHttpRequest();
      xhttp.open("GET", url,false);

      xhttp.send();

      var parse = xhttp.responseText;
      console.log('Brut',parse);
      return parse;
    }

    parseUrl(start,end,methode) {
      const url = this.buildUrl(start, end, methode);
      const jsonResult = this.callMamp(url);
      console.log('url',url);
      console.log('json',jsonResult);
      const jsonReact1 = JSON.parse(jsonResult);
      
      console.log('Jsonreact1',jsonReact1);
      return jsonReact1;

    }
  
    handleOriginChange(event) {
  
      this.setState({
        start: event.target.value
      });
    }
    handleDestinationChange(event) {
  
      this.setState({
        end: event.target.value
      });
    }

    handleJourneyTypeChange(event) {
      this.setState({methode: event.target.value});
      console.log(this.state)
    }

    handleSubmit(event) {
      event.preventDefault();

      const {start, end, methode} = this.state;
      const json = this.parseUrl(start,end,methode);
      if((start > 0 && start < 96) && (end > 0 && end < 96))
      {
          this.setState({
          data:json,
          onSubmit:true
        })
      }


    }

    cards(start,end,methode,data) {
      for(var i = 0; i < 10; i++) {
          return(
            <Card data={data} index={i} start={start} end={end} methode={methode}/>
          );
      }
    };

    handleChangeCheckbox(e) {
      const item = e.target.name;
      const isChecked = e.target.checked;
      this.setState(prevState => ({ checkedItems: prevState.checkedItems.set(item, isChecked) }));
    }
  

    render() {
      const {start, end, methode, data} = this.state;
      let card =[];
      for(var i = 0; i < 10; i++) {
        if(data && data[i])
          card.push(<Card data={data} index={i} start={start} end={end} methode={methode}/>)
      }
      return (
        <div className='App'>
          <header className='App-header'>
            <a href ="http://localhost:8888/PTS_AB_GG_MGDB/index.html#about">Retourner au site web</a>
            <form onSubmit={this.handleSubmit}>
              <br/>
              <label>
                Départ :  &nbsp;
                <input
                  name='start'
                  type='number'
                  value={start}
                  placeholder={'Chiffres entre 1 et 95'}
                  onChange={this.handleOriginChange} />
              </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <label>
                Destination : &nbsp;
                <input
                  name='end'
                  type='number'
                  value={end}
                  placeholder={'Chiffres entre 1 et 95'}
                  onChange={this.handleDestinationChange} />
              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <br/>
              <br/>
              <label>
                Type de trajet : &nbsp;
                <select value={methode} onChange={this.handleJourneyTypeChange}>
                  <option value='BrutPower'>Tous chemins</option>
                  <option value='Dijkstra'>Le plus rapide</option>
                  <option value='FordFulkerson'>Le plus court</option>
                </select>
              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <label>
                Restrictions : &nbsp;  
                <React.Fragment>
                  {
                    checkboxes.map(item => (
                      <label key={item.key}>
                        {item.name}
                        
                        <Checkbox name={item.name} checked={this.state.checkedItems.get(item.name)} onChange={this.handleChangeCheckbox } />
                      </label> 
                    )) 
                  }
                </React.Fragment>
              </label>&nbsp;&nbsp;&nbsp;&nbsp;
              <br/> 
              <br/>
              <button className="Bouton" type="submit">Rechercher</button>
            </form>
          </header>

          <div className='Journeys'>
          {this.state.onSubmit === true && !(this.state.data.query.methode === 'b') && <Card data={this.state.data} index={0} start={start} end={end} methode={methode}/>}

          {(this.state.onSubmit === true && this.state.data.query.methode === 'b') && card}
          </div>
        </div>
      );
    }
}  
  export default CardContainer;
  