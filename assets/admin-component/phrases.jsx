import {Http} from "./http";

const {
    FormGroup,
    Input,
    Button,
    ListGroup,
    ListGroupItem
} = Reactstrap;

export class Phrases extends React.Component {

    constructor() {
        super();
        this.state = {
            newPhrase: {
                content: '',
                language: 'fr',
                url_source: ''
            },
            newPhrases: []
        };

        this.handleContentChange = this.handleContentChange.bind(this);
        this.handleLanguageChange = this.handleLanguageChange.bind(this);
        this.handleSourceChange = this.handleSourceChange.bind(this);
        this.renderPhrases = this.renderPhrases.bind(this);
        this.fetchPhrases = this.fetchPhrases.bind(this);
        this.addCustomPhrase = this.addCustomPhrase.bind(this);
        this.handlePhrase = this.handlePhrase.bind(this);
        this.isNewPhrase = this.isNewPhrase.bind(this);

    }

    handleContentChange(event) {
        let np = this.state.newPhrase;
        //strip html tags
        np.content = event.target.value.replace(/<(?:.|\n)*?>/gm, '');
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    handleLanguageChange(event) {
        let np = this.state.newPhrase;
        np.language = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    handleSourceChange(event) {
        let np = this.state.newPhrase;
        np.url_source = event.target.value;
        this.setState(
            {
                newPhrase: np
            }
        )
    }

    addCustomPhrase(phrase) {
        this.setState({
            newPhrase: {
                content: '',
                language: 'fr',
                url_source: ''
            }
        });
        this.handlePhrase(phrase);
    }

    handlePhrase(phrase) {
        let ph = this.props.phrases;
        let nph = this.state.newPhrases;
        if (this.isNewPhrase(phrase)) {
            ph.push(phrase);
            nph = nph.filter((phr) => phr.content !== phrase.content);
        }
        else {
            ph = this.props.phrases.filter((phr) => phr.content !== phrase.content);
            nph.push(phrase);
        }
        this.props.updatePhrases(ph);
        this.setState((prevState, props) => {
            return {
                newPhrases: nph,
            }
        });
    }

    fetchPhrases() {
        Http.fetchPhrases(this.props.term).then((result) => {
                result = result.filter((p) => this.isNewPhrase(p));
                this.setState((prevState, props) => {
                    return {
                        newPhrases: result,
                    }
                });
            }
        )
    }

    isNewPhrase(phrase) {
        return !Boolean(this.props.phrases.find((p) => p.content === phrase.content));
    }

    renderPhrases() {
        let phrases = this.props.phrases.concat(this.state.newPhrases);

        return (
            <ListGroup>
                {phrases.map((phrase, key) =>
                    <ListGroupItem
                        key={key}>
                        {phrase.content}
                        {phrase.url_source ? <span>&nbsp;[<a href={phrase.url_source} target='_blank'>source</a>]</span> : ''}
                        <Button className="float-right"
                                size="lg"
                                outline
                                onClick={() => this.handlePhrase(phrase)}
                                color={this.isNewPhrase(phrase) ? 'success' : 'danger'}
                        >{this.isNewPhrase(phrase) ? '+' : '-'}</Button>
                    </ListGroupItem>
                )}
            </ListGroup>
        )
    }

    render() {
        return (
            <div className="phrases component">
                {this.renderPhrases()}
                <FormGroup>
                    <Button outline block color="info" onClick={this.fetchPhrases}>Fetch phrases for "{this.props.term}"</Button>
                </FormGroup>
                <FormGroup>
                    <Input type="textarea" value={this.state.newPhrase.content} placeholder="content"
                           onChange={this.handleContentChange}/>
                </FormGroup>
                <FormGroup>
                    <Input type="text" value={this.state.newPhrase.url_source} placeholder="source url"
                           onChange={this.handleSourceChange}/>
                </FormGroup>

                <FormGroup>
                    <Input type="text" value={this.state.newPhrase.language} placeholder="language"
                           onChange={this.handleLanguageChange}/>
                </FormGroup>

                <FormGroup>
                    <Button outline block color="success" onClick={() => this.addCustomPhrase(this.state.newPhrase)}>+</Button>
                </FormGroup>
            </div>
        )
    }
}