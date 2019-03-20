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
            result = 'Chemin le plus court'
        }
        else if(methode === 'Dijkstra')
        {
            result = 'Chemin le plus rapide'
        }
        else {      
            result = 'Tous chemins'
        }
        return result;
    }



    render() {
        const data = this.props.data;
        const {query} = data;
            return(
                <div>
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
                                {data && data[this.props.index] && data[this.props.index].totalMinuteTime && 
                                    ('Duration : ' + data[this.props.index].totalMinuteTime + ' minutes')}
                                    {(!data || !data[this.props.index] || !data[this.props.index].totalMinuteTime) && ('Aucun résultat trouvé') }
                                </div>
                            </div>
                            <div className='Parcours'>Parcours : <br/>
                                {data && data[this.props.index] && data[this.props.index].ways && <p className='Etapes'>
                                    {query.start +' -> '+ data[this.props.index].ways.map(way => way + ' -> ') +  query.end}
                                </p>}
                                {(!data || !data[this.props.index] || !data[this.props.index].ways) &&  ('Aucun résultat trouvé') }
                            </div>
                        </div>
                        <button className='Bouton'>
                            Aller à
                        </button>
                    </div>
                    <br/>
                </div>
            );
        }
    };



export default Card;