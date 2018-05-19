import {Speaker} from "./util";

export class Phrases extends React.Component {
    constructor(){
        super();

        this.replaceWords = this.replaceWords.bind(this);
        this.replaceWithMarker = this.replaceWithMarker.bind(this);
        this.highlightWords = this.highlightWords.bind(this);

    }

    replaceWords(text, replaceFn){
        let needles = this.props.mainWord.inflections.map((inf) => inf.inflection);
        needles.push(this.props.mainWord.lemma);

        let words = text.split(/(,|\s|!|\.|-)/);
        needles.forEach((needle) => {
            words = words.map((word, key) => {
                return word === needle ? <span key={key}>{replaceFn(needle)}</span> : word;
            });
            //text = words.includes(needle) ? text.replace(needle, replaceFn(needle)) : text;
        });
        return words;
    }

    replaceWithMarker(text){
        return this.replaceWords(text, (needle) => '*'.repeat(needle.length))
    }

    highlightWords(text){
        return this.replaceWords(text, (needle) => {
            return <span className="inflection">{needle}</span>
        })
    }

    render(){
        return(
            <ul className="phrases">
                {this.props.phrases.map((phrase, key) =>
                    <li key={key}>
                        {this.props.showResults ?
                           <span> {this.highlightWords(phrase.content)}
                                <button onClick={() => Speaker.speak(phrase.content)} className="btn btn-light">
                                <span className="oi oi-media-play"></span>
                                </button>
                           </span>
                            :
                            this.replaceWithMarker(phrase.content)
                        }
                    </li>)}
            </ul>
        )
    }
}