<template>
  <tr>
    <td class="border">{{ address.name }}</td>
    <td class="border">{{ address.phoneNumber }}</td>
    <td class="border">{{ address.street }}</td>
    <td class="border">{{ address.postCode }}</td>
    <td class="border">{{ address.city }}</td>
    <td class="border">
      <button
          class="btn btn-primary"
          @click.prevent="deleteAddress"
      >
        Delete
      </button>
    </td>
    <td class="border">

      <button id="show-modal" @click="showModal = true">Edit</button>
    </td>

    <teleport to='body'>
      <edit-modal v-if="showModal"
                  @close="showModal = false"
                  :name="address.name"
                  :phoneNumber="address.phoneNumber"
                  :id="address.id"
                  :street="address.street"
                  :postCode="address.postCode"
                  :city="address.city"

                  v-on:close="refresh"
      />
    </teleport>

  </tr>
</template>

<script>
import axios from 'axios';
import EditModal from './EditModal';
import config from "../config";

export default {
  name: 'Row',
  props: {
    address: {},
  },

  components: {
    EditModal
  },

  data() {
    return {
      showModal: false
    }
  },

  methods: {
    async deleteAddress() {
      const response = await axios.delete(config.BASE_URL + `/address/${this.$props.address.id}`);

      if (response.status === 204 || response.status === 200) {
        this.$emit('refresh');
      }
    },

    refresh() {
      this.$emit('refresh');
    }
  }
}
</script>
