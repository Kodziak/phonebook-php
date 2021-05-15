<template>
  <transition name="modal">
    <div id="modal" class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container">

          <h2 class="modal-header text-xl uppercase">
            <slot name="header">
              Edit user
            </slot>
          </h2>

          <div class="modal-body">
            <slot name="body">
              <div class="flex flex-col mx-auto my-10">
                <label for="name">Name:</label>
                <input
                    id="name"
                    type="text"
                    class="border border-solid mb-2 text-black" v-model="nameN"
                >

                <label for="phone-number">Phone number:</label>
                <input
                    id="phone-number"
                    type="tel"
                    pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"
                    class="border border-solid mb-2 text-black" v-model="phoneN"
                >

                <label for="street">Street:</label>
                <input id="street" type="text" class="border border-solid mb-2 text-black" v-model="streetN">

                <label for="post-code">Post code:</label>
                <input id="post-code" type="text" class="border border-solid mb-2 text-black" v-model="postCodeN">

                <label for="city">City:</label>
                <input id="city" type="text" class="border border-solid mb-2 text-black" v-model="cityN">
              </div>
            </slot>
          </div>

          <div class="modal-footer flex">
            <slot name="footer">
              <button @click.prevent="$emit('close')" class="btn btn-primary bg-white text-black my-3 p-2 w-1/2 mr-1">
                Cancel
              </button>
              <button @click.prevent="submit" class="btn btn-primary bg-white text-black my-3 p-2 w-1/2 ml-1">OK
              </button>
            </slot>
          </div>
        </div>
      </div>
    </div>
  </transition>
</template>

<script>
import axios from 'axios';
import config from '../config.js';

export default {
  name: 'EditModal',
  props: [
    'name', 'phoneNumber', 'id', 'street', 'postCode', 'city',
  ],

  data() {
    return {
      nameN: '',

      phoneN: '',
      streetN: '',
      postCodeN: '',
      cityN: '',
    };
  },

  created() {
    this.nameN = this.$props.name;
    this.phoneN = this.$props.phoneNumber;
    this.streetN = this.$props.street;
    this.postCodeN = this.$props.postCode;
    this.cityN = this.$props.city;
  },

  methods: {
    async submit() {
      const userData = {
        name: (this.nameN === '') ? this.$props.name : this.nameN,
        phoneNumber: (this.phoneN === '') ? this.$props.phoneNumber : this.phoneN,
        street: (this.streetN === '') ? this.$props.street : this.streetN,
        postCode: (this.postCodeN === '') ? this.$props.postCode : this.postCodeN,
        city: (this.cityN === '') ? this.$props.city : this.cityN,
      };

      await axios.put(config.BASE_URL + `/address/${this.$props.id}`, userData);

      this.$emit('close');
    },
  },
};
</script>

<style>
#modal {
  position: fixed;
  top: 0;
  right: 0;
  width: 100%;
  height: 100%;
  background: black;
  color: white;
  z-index: 1000;
}

.modal-wrapper {
  height: 100%;
  margin: auto;
  display: flex;
  width: 400px;
}

.modal-container {
  width: 100%;
  margin: auto 0;
}
</style>
