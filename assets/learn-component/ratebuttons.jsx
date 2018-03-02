export class RateButtons extends React.Component {

    renderButton(i) {
        return (
            <button onClick={() => this.props.handleRate(i)} key={i} type="submit" className="btn btn-light btn-lg"
                    name="learn_rating" value={i}>{i}
            </button>
        )
    }

    render() {
        let buttons = [];

        for (let i = 0; i < 6; i++) {
            buttons.push(
                this.renderButton(i)
            )
        }

        return (
            <div className="rate">
                {buttons}
            </div>
        )
    }
}