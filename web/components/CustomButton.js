
/*
    Create a button with handler and value props. The button will call the handler function on click and pass value as it's parameter
 */
class CustomButton extends React.Component {

    constructor(props) {
        super(props);
        this.handleClick = this.handleClick.bind(this);
    }

    handleClick() {
        this.props.handler(this.props.value);
    }

    filterProps(props) {
        let {handler, value, ...rest} = props;
        return rest;
    }

    render() {
        let filteredProps = Object.assign(this.filterProps(this.props), {onClick: this.handleClick});
        return React.createElement("button", filteredProps, this.props.children);
    }
}