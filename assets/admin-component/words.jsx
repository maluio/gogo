const routes = require('../../public/js/fos_js_routes.json');
import Routing from '../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

class Word {
    constructor(chars, language) {
        this.chars = chars;
        this.language = language;
        this.gender = gender;
    }
}

export class Words extends React.Component {

    constructor(){
        super();
        this.state = {
            term: '',
            translation: ''
        };

        this.translate = this.translate.bind(this);
        this.handleTranslateState = this.handleTranslateState.bind(this);

    }

    handleTranslateState(event){
        let term = event.target.value;
        this.setState({term: term});
    }

    translate(){

        fetch(
            Routing.generate('translate', {term: this.state.term}),
            {
                method: 'GET',
                credentials: 'same-origin'
            })
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState((prevState, props) => {
                        return {
                            translation: result.translatedText,
                        }
                    });
                },
                (error) => {
                }
            )
    }

    render (){
        return (
            <div className="words">
                <input
                    className="form-control"
                    value={this.state.term}
                    onChange={this.handleTranslateState}
                    type="text"
                    placeholder="search word(s)"
                />
                <button onClick={this.translate}>Translate</button>
                <input
                    className="form-control"
                    value={this.state.translation}
                    type="text"
                    placeholder="translation"
                />
            </div>
        )
    }
}