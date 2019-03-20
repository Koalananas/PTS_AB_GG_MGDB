import React from 'react';
import './../Styles/Card.css';


class Card extends React.Component {
    constructor(props){
        super(props);
        this.state = { 
        }
        
        this.methodeConversion =this.methodeConversion.bind(this);
    }


    methodeConversion(methode) {
        var result = 'Tous chemins';
        if(methode === 'FordFerkuson')
        {
            result = 'Chemin le plus rapide'
        }
        else if(methode === 'Dijkstra')
        {
            result = 'Chemin le plus coourt'
        }
        else {      
            result = 'Tous chemins'
        }
        return result;
    }



    render() {
        const data = this.props.data;
        const {query, description} = data;
            return(
                <div className='Card'>
                    <div className='Journey'>
                        <div className='Infos'>
                            <div className='Criteres'>
                                {"Départ : "+ query.start }<br/>
                                {" Destination : " + query.end} <br/><br/>
                                <div className='Methodes'>
                                    {" Methode : " + this.methodeConversion(query.methode)}
                                </div>
                            </div>
                            <div className='Specifiques'>
                                {'Duration : ' + data[this.props.index].totalMinuteTime + ' micros'}
                            </div>
                        </div>
                        <div className='Parcours'>Parcours : <br/>
                            <p className='Etapes'>
                                {query.start +' -> '+ data[this.props.index].ways.map(way => way + ' -> ') +  query.end}
                            </p>
                        </div>
                    </div>
                    <button className='Bouton'>
                        Aller à
                    </button>
                </div>
            );
        }
    };



export default Card;