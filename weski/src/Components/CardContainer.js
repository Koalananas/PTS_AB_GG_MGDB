import React, {Component} from 'react';
import Card from './Card';
import Checkbox from './Checkbox';
import './../Styles/App.css';

const items = [

  'Tous',
  'Vertes',
  'Bleus',
  'Rouges',
  'Noires'
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
        }
        //isEnabled:  false
      };
  
      this.handleOriginChange = this.handleOriginChange.bind(this);
      this.handleDestinationChange = this.handleDestinationChange.bind(this);
      this.handleJourneyTypeChange = this.handleJourneyTypeChange.bind(this);
      this.handleSubmit = this.handleSubmit.bind(this);
      this.parseUrl  = this.parseUrl.bind(this);
      this.callMamp  = this.callMamp.bind(this);
      this.buildUrl = this.buildUrl.bind(this);
      this.cards = this.cards.bind(this);
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
        else if(methode === 'FordFerkuson'){
          url += 'methode=f';      
        }
        else {
          url += 'methode=b';
        }
      }
      // if(restriction !== null){

      // }
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
      //jsonReact = JSON.parse(jsonReact);

      const jsonReact = '{"0":{"ways":[2,3,4,5,71,68,73,69,54],"totalMinuteTime":115.55999999999999},"1":{"ways":[2,3,4,68,73,69,54],"totalMinuteTime":101.05999999999999},"2":{"ways":[2,3,73,32,31,69,54],"totalMinuteTime":87.98},"3":{"ways":[2,3,73,69,54],"totalMinuteTime":57.46000000000001},"4":{"ways":[2,37,32,31,69,54],"totalMinuteTime":81.72},"5":{"ways":[2,37,69,54],"totalMinuteTime":51.2},"6":{"ways":[6,1,2,3,73,69,54],"totalMinuteTime":89.26},"7":{"ways":[6,1,2,37,69,54],"totalMinuteTime":83},"8":{"ways":[6,36,54],"totalMinuteTime":33.38},"9":{"ways":[6,50,75,54],"totalMinuteTime":38.88},"description":"Chaque index numerote de l objet principal correspond a une possibilie de chemin a prendre pour aller du  point a au point b La liste de ways sont les chemins successif a prendre, totalMinuteTime est le nombre de minute pour relier le point a au point b via les chemins lists avant.","computeTimeInS":0.006906986236572266,"methode":"Brute Force","query":{"start":5,"end":10,"methode":"b"}}';
      //const jsonReact = JSON.parse('{"0":{"ways":[17,93,5],"totalMinuteTime":28.1},"description":"L'index 0 de cette objet donne la suite de chemin a prendre pour aller du point a au point b le plus rapidement avec Dijkstra","computeTimeInS":0.012394905090332031,"methode":"Dijkstra","query":{"start":10,"end":1,"methode":"d"}}');
      console.log('Jsonreact1',jsonReact1);
      console.log('Jsonreact',jsonReact);
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
    }

    handleSubmit(event) {
      event.preventDefault();
      
      console.log(this.state);
  
      for (const checkbox of this.selectedCheckboxes) {
        console.log(checkbox, 'is selected.');
      }
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

    createCheckbox = label => (
      <Checkbox
              label={label}
              handleCheckboxChange={this.toggleCheckbox}
              key={label}
          />
    )

    createCheckboxes = () => (
      items.map(this.createCheckbox)
    )
  
  

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
                  <option value='FordFerkuson'>Le plus court</option>
                </select>
              </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              <label>
                Restrictions : &nbsp;
                  {this.createCheckboxes()}
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
  