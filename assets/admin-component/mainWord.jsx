const {
    Form,
    FormGroup,
    Input,
    Label,
    Button,
    ListGroup,
    ListGroupItem
} = Reactstrap;

export class MainWord extends React.Component {

    constructor() {
        super();
        this.state = {
            newInflection: ''
        };

        this.handleLemmaChange = this.handleLemmaChange.bind(this);
        this.handleGenderChange = this.handleGenderChange.bind(this);
        this.addInflection = this.addInflection.bind(this);
        this.removeInflection = this.removeInflection.bind(this);
        this.handleNewInflectionChange = this.handleNewInflectionChange.bind(this);

    }

    handleLemmaChange(event) {
        let mw = this.props.mainWord;
        mw.lemma = event.target.value;
        mw.lemma = mw.lemma.trim();
        this.props.updateMainWord(mw);
    }

    handleGenderChange(event) {
        let mw = this.props.mainWord;
        mw.gender = event.target.value;
        this.props.updateMainWord(mw);
    }

    addInflection() {
        let mw = this.props.mainWord;
        mw.inflections.push({
            inflection: this.state.newInflection
        });
        this.props.updateMainWord(mw);
    }

    removeInflection(inflection) {
        let mw = this.props.mainWord;
        mw.inflections = mw.inflections.filter((frm) => frm.inflection !== inflection.inflection);
        this.props.updateMainWord(mw);
    }

    handleNewInflectionChange(event) {
        this.setState({
            newInflection: event.target.value
        })
    }

    render() {
        return (
            <div className="mainWord">
                <Form>
                    <FormGroup>
                        <h1>{this.props.mainWord.lemma}</h1>
                        <Label for="lemma">Lemma</Label>
                        <Input
                            id="lemma"
                            className="form-control"
                            value={this.props.mainWord.lemma}
                            onChange={this.handleLemmaChange}
                            type="text"
                            placeholder="enter word"
                        />
                    </FormGroup>
                    <FormGroup>
                        <select
                            value={this.props.mainWord.gender}
                            onChange={this.handleGenderChange}
                            className="form-control"
                        >
                            <option value="">gender</option>
                            <option value="m">male</option>
                            <option value="f">female</option>
                        </select>
                    </FormGroup>
                </Form>
                <ListGroup>
                    {this.props.mainWord.inflections.map((inflection, key) =>
                        <ListGroupItem key={key}>
                            <Button color="danger" onClick={() => this.removeInflection(inflection)}>Remove</Button>
                            &nbsp;
                            {inflection.inflection}
                        </ListGroupItem>
                    )}
                </ListGroup>
                    <Form inline>
                    <FormGroup>
                        <Input value={this.state.newInflection} onChange={this.handleNewInflectionChange}/>
                        <Button color="success" onClick={this.addInflection}>Add</Button>
                    </FormGroup>
                </Form>
            </div>
        )
    }

}