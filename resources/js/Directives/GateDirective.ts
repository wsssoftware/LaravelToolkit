import {DirectiveBinding} from "vue";
import Gate from "../Gate";

function handle(el: HTMLElement, binding: DirectiveBinding): void {
    let gate = binding.instance?.$gate as Gate;
    let value: string = binding.value;
    let arg: string = binding.arg as string;
    if (!gate) {
        console.log('To use gate helper you need to setup LaravelToolkit VueJS plugin');
        return;
    }
    if (!['allows', 'any', 'denies', 'none'].includes(arg)) {
        console.error('Directive argument must be "allows", "any", "denies" or "none"')
        return;
    }
    el.style.visibility = gate[arg](value) ? 'visible' : 'hidden';
}

export default {
    beforeMount: handle,
    updated: handle,
}
