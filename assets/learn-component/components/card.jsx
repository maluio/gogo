import {HtmlRaw} from "./util";

// highlight js
let highlightJs = require('../../lib/highlightJs/highlightJs.min');
//require('../../lib/highlightJs/highlightJs-solarized-dark.css');

export class Card extends React.Component {

    componentDidMount() {
        this.highlightCode();
    }
    componentDidUpdate() {
        this.highlightCode();
    }

    highlightCode() {
        const domNode = ReactDOM.findDOMNode(this);
        const nodes = domNode.querySelectorAll('pre code');
        nodes.forEach((block) => highlightJs.highlightBlock(block));
    }

    speak(text){
        //strip html tags
        text = text.replace(/<(?:.|\n)*?>/gm, '');
        let synth = window.speechSynthesis;
        let voices = synth.getVoices();
        voices = voices.filter(voice => voice.lang === 'fr-FR');

        let utterThis = new SpeechSynthesisUtterance(text);
        utterThis.onend = function (event) {
            //console.log('SpeechSynthesisUtterance.onend');
        };
        utterThis.onerror = function (event) {
            //console.error('SpeechSynthesisUtterance.onerror');
        };
        utterThis.voice = voices[0];
        //utterThis.pitch = pitch.value;
        // utterThis.rate = rate.value;
        synth.speak(utterThis);


    }

    render() {
        return (
            <div>
                <button onClick={() => this.speak(this.props.content)} className="btn btn-light">
                    <span className="oi oi-media-play"></span>
                </button>
                <div className="card card-outline-secondary mb-3">
                    <div className="card-body">
                        <HtmlRaw raw={this.props.content}/>
                    </div>
                </div>
            </div>
        )
    }
}