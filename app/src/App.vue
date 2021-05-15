<template>
  <div id="app" class="box-border container mx-auto">
    <img alt="Vue logo" src="./assets/logo.png" class="mx-auto">

    <form class="flex flex-col w-1/4 sm:w-1/2 mx-auto my-10">
      <label for="name">Name:</label>
      <input id="name" type="text" class="border border-solid" v-model="address.name" required>

      <label for="phone-number">Phone number:</label>
      <input id="phone-number"
             type="tel"
             onkeypress="return event.charCode >= 48 && event.charCode <= 57"
             pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"
             class="border border-solid"
             v-model="address.phoneNumber"
             required
      >

      <label for="street">Street:</label>
      <input id="street" type="text" class="border border-solid" v-model="address.street" required>

      <label for="post-code">Post code:</label>
      <input id="post-code" type="text" class="border border-solid" v-model="address.postCode" required>

      <label for="city">City:</label>
      <input id="city" type="text" class="border border-solid" v-model="address.city" required>

      <button @click.prevent="addAddress" class="btn btn-primary bg-black text-white my-3 p-2">Add place</button>
    </form>

    <form class="flex flex-col w-1/4 sm:w-1/2 mx-auto my-10">
      <label for="name">Name:</label>
      <input id="name" type="text" class="border border-solid" v-model="coordinates.name" required>

      <label for="phone-number">Phone number:</label>
      <input id="phone-number"
             type="tel"
             onkeypress="return event.charCode >= 48 && event.charCode <= 57"
             pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}"
             class="border border-solid"
             v-model="coordinates.phoneNumber"
             required
      >

      <label for="latitude">Latitude:</label>
      <input id="latitude" type="text" class="border border-solid" v-model="coordinates.latitude" required>

      <label for="longitude">Longitude:</label>
      <input id="longitude" type="text" class="border border-solid" v-model="coordinates.longitude" required>

      <button @click.prevent="addCoordinates" class="btn btn-primary bg-black text-white my-3 p-2">Add place</button>
    </form>

    <table class="table-auto border-collapse border mx-auto my-10">
      <thead>
      <tr>
        <th class="border bg-green-200 border-green-600"
            v-for="(column, key) in ['Name', 'Phone number', 'Street', 'Post code', 'City', 'Delete', 'Edit']"
            :key="key">
          {{ column }}
        </th>
      </tr>
      </thead>
      <tbody>
      <Row :address="address" v-for="(address, key) in addresses" :key="key" v-on:refresh="refresh"></Row>
      </tbody>
    </table>

    <v-map v-if="componentState && addresses.length > 0" :addresses="addresses"/>
  </div>
</template>

<script>
import axios from 'axios';
import Row from './components/Row.vue';
import vMap from './components/Map.vue';

import config from './config.js';

export default {
  name: 'App',

  components: {
    Row,
    vMap
  },

  data() {
    return {
      addresses: [],
      longLatArr: [],
      componentState: true,

      address: {
        name: '',
        phoneNumber: '',
        street: '',
        postCode: '',
        city: ''
      },

      coordinates: {
        name: '',
        phoneNumber: '',
        latitude: '',
        longitude: ''
      }
    }
  },

  async created() {
    try {
      this.addresses = await this.getAddresses();
    } catch(e) {
      console.error('Heroku server dead. Trying again...')
      this.addresses = await this.getAddresses();
    }
  },

  methods: {
    async getAddresses() {
      const users = await axios.get(config.BASE_URL + '/address');
      return users.data;
    },

    async refresh() {
      this.addresses = await this.getAddresses();

      this.componentState = false;
      this.$nextTick().then(() => {
        this.componentState = true;
      });
    },

    resetFields() {
      this.address = {
        name: '',
        phoneNumber: '',
        street: '',
        postCode: '',
        city: '',
      };

      this.coordinates = {
        name: '',
        phoneNumber: '',
        longitude: '',
        latitude: ''
      }
    },

    async addAddress() {
      const response = await axios.post(config.BASE_URL + '/address', this.address);

      if (response.status === 201 || response.status === 200) {
        this.resetFields();
        await this.refresh();
      } else {
        console.log('Naucz sie into requesty')
      }
    },

    async addCoordinates() {
      const response = await axios.post(config.BASE_URL + '/coordinates', this.coordinates);

      if (response.status === 201 || response.status === 200) {
        this.resetFields();
        await this.refresh();
      } else {
        console.log('Naucz sie into requesty')
      }
    }
  }
}
</script>

<style>

</style>
