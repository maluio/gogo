export class HtmlRaw extends React.Component {

    createMarkup() {
        return {__html: this.props.raw};
    }

    render() {

        return (
            <div dangerouslySetInnerHTML={this.createMarkup()}/>
        )
    }
}

export class Speaker {
    static speak(text){
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
}