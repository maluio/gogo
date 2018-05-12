import {Speaker} from "./util";

export class Phrases extends React.Component {
    constructor(){
        super();
    }

    render(){
        return(
            <ul className="phrases">
                {this.props.phrases.map((phrase, key) =>
                    <li key={key}>
                        {this.props.showResults ?
                           <span> {phrase.content}
                                <button onClick={() => Speaker.speak(phrase.content)} className="btn btn-light">
                                <span className="oi oi-media-play"></span>
                                </button>
                           </span>
                            :
                            phrase.content.replace(this.props.mainWord.lemma, '***')
                        }

                    </li>)}
            </ul>
        )
    }
}