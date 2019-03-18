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
        var count = 0;
        const {query, description} = data;
        while(this.props.data[count] !== 9){

            console.log(count);
            count = count +1;
            return(
                <div className='Card'>
                    <div className='Journey'>
                        <div className='Infos'>
                            <div className='Criteres'>
                                {"Origine : "+ query.start +  " Destination : " + query.end + " Methode : " + this.methodeConversion(query.methode)}
                            </div>
                            <div className='Specifiques'>
                                {description}
                            </div>
                        </div>
                        <div className='Parcours'>
                            {query.start +' ,'+ data[count].ways.map(way => way + ' ') +','+  query.end}
                        </div>
                    </div>
                    <button className='Bouton'>
                        {}
                    </button>
                </div>
            )
        }
        count = 0;
    }
};


export default Card;