<template>
	<div>
		<div>
			<button
				class="btn btn-success btn-xs"
				v-show="showFormAddPaysButon"
				@click="
					showFormAddPays = true;
					showFormAddPaysButon = false;
					show_button_submit = true;
					show_tool_tip = false;
				"
			>
				Добавить размер зп
			</button>
		</div>
		<transition name="show_form">
			<form v-show="showFormAddPays" @submit="method_submitChanges" id="form-add-pays" class="big-form">
				<div class="container-fluid">
					<div class="row" style="background-color: rgba(0,0,0,0.1);">
						<div class="col-xs-12 text-center">
							<h4>Добавление настройки для размера З/П выбранной должности на выбранной точке продаж</h4>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-6">
							<!-- dolgnost sale_point date
						{{ sp }} - {{ dolgnost }} -->

							<h4>Дата назначения</h4>

							<table class="table xtable-sm table-bordered">
								<tr>
									<td>с указанной даты будут работать новые параметры</td>
									<td>
										<input
											type="date"
											v-model="form_add['date']"
											placeholder=""
											required="required"
										/>
									</td>
								</tr>
							</table>

							<h4>Условия по месячному обороту</h4>

							<table class="table table-sm table-bordered">
								<!-- <tr>
								<td>если оборот месяца равен или менее</td>
								<td>
									<input
										type="number"
										v-model="form_add['oborot_sp_last_monht_menee']"
										placeholder=""
									/>
								</td>
							</tr> -->
								<tr>
									<td>если оборот месяца равен или более</td>
									<td>
										<input
											type="number"
											v-model="form_add['oborot_sp_last_monht_bolee']"
											placeholder=""
										/>
									</td>
								</tr>
								<!-- </table>

						<h4>условия по дневному обороту</h4>

						<table class="table table-sm table-bordered"> -->
								<tr>
									<td>если дневной оборот равен или более</td>
									<td>
										<input
											type="number"
											v-model="form_add['pay_from_day_oborot_bolee']"
											placeholder=""
										/>
									</td>
								</tr>
							</table>

							<p class="alert-info" style="padding: 10px;">
								указывайте обороты начиная с большей границы к меньшей .. самая меньшая начинается с
								того параметра где не указана сумма оборота <br />например ( 1 декабря изменение )
								<br />1 запись - оборот больше или равен 5 000 000 - з/п 150 <br />2 запись - оборот
								больше или равен 1 000 000 - з/п 100 <br />3 запись - оборот больше или равен ( не
								указано ) - з/п 50
							</p>
						</div>
						<div class="col-xs-12 col-sm-6">
							<h4>Размер з/п в час (рублей)</h4>

							<table class="table table-sm table-bordered">
								<tr>
									<td colspan="2">фиксированная</td>
									<td>
										<input
											type="number"
											v-model="form_add['ocenka-hour-base']"
											placeholder=""
											max="9000"
											min="1"
											step="1"
											size="7"
										/>
										руб/час
									</td>
								</tr>
								<tr>
									<td rowspan="2">оценка 5</td>
									<td>з\п</td>
									<td>
										<input
											type="number"
											v-model="form_add['ocenka-hour-5']"
											placeholder=""
											max="9000"
											min="1"
											step="1"
											size="7"
										/>
										руб/час
									</td>
								</tr>
								<tr>
									<td>бонус</td>
									<td>
										<input
											type="number"
											v-model="form_add['premiya-5']"
											placeholder=""
											max="9000"
											min="1"
											step="1"
										/>
									</td>
								</tr>
								<tr>
									<td rowspan="2">оценка 3</td>
									<td>з\п</td>
									<td>
										<input
											type="number"
											v-model="form_add['ocenka-hour-3']"
											placeholder=""
											max="9000"
											min="1"
											step="1"
											size="7"
										/>
										руб/час
									</td>
								</tr>
								<tr>
									<td>бонус</td>
									<td>
										<input
											type="number"
											v-model="form_add['premiya-3']"
											max="9000"
											min="1"
											step="1"
											placeholder=""
										/>
									</td>
								</tr>
								<tr>
									<td colspan="2">ежедневный бонус ( % от дневного оборота)</td>
									<td>
										<input
											type="number"
											v-model="form_add['bonus_proc_from_oborot']"
											placeholder=""
											max="50"
											min="0.1"
											step="0.1"
											size="5"
										/>
										%
									</td>
								</tr>
							</table>

							<h4>Изменение з/п в час</h4>

							<table class="table table-bordered">
								<tr>
									<td colspan="2">если курит</td>
									<td>
										<input
											type="number"
											v-model="form_add['if_kurit']"
											placeholder=""
											max="-1"
											min="-999"
											step="1"
											size="7"
										/>
										руб/час
									</td>
								</tr>
							</table>

							<!-- <form v-show="toggleDialog" @submit="submitChanges"> -->
							<!-- <br />
						<div style="border: 1px solid gray;">
							<input v-model="form_add.name" placeholder="enter name..." />
							<br />
							<input v-model="form_add.profession" placeholder="enter profession..." />
							<br />
							<input v-model="form_add.age" placeholder="enter age..." />
							<br />
							<button class="btn btn-xs btn-success" type="submit">Submit changes</button> -->
							<!-- </form> -->
						</div>
					</div>

					<div class="row">
						<div class="col-xs-12 text-center">
							<center>
								<button
									class="btn btn-xs btn-success"
									v-show="show_button_submit"
									@click="show_tool_tip = true"
									type="submit"
								>
									Добавить
								</button>
								<div
									v-show="show_tool_tip"
									class="alert-warning"
									style=" display: inline-block; margin-left: 10px; padding:15px;"
								>
									Добавляю, секунду пожалуйста
								</div>
								<div
									v-show="show_tool_tip_danger"
									class="alert-danger"
									style=" display: inline-block; margin-left: 10px; padding:15px;"
								>
									Внимание, произошла ошибка: {{ show_tool_tip_danger_text }}<br />
									через 5 минут - Обновите страницу и попробуйте повторно, если не получится,
									обратитесь к администратору
								</div>
							</center>
						</div>
					</div>
				</div>
			</form>
		</transition>
	</div>
</template>

<script>
// import { mapMutations } from "vuex";
import { mapGetters, mapActions } from 'vuex';

export default {
	data() {
		return {
			showFormAddPays: false,
			showFormAddPaysButon: true,
			// activeSp: "",
			// title: "",
			// load_pays: 0,

			show_tool_tip: false,
			show_button_submit: true,

			show_tool_tip_danger: false,
			show_tool_tip_danger_text: '',
			form_add: {
				// name: null,
				// profession: null,
				// age: null,

				// dolgnost: dolgnost,
				// sale_point: sp,
				dolgnost: null,
				sale_point: null,

				date: null,
				'ocenka-hour-base': null,
				'ocenka-hour-5': null,
				'premiya-5': null,
				'ocenka-hour-4': null,
				'premiya-4': null,
				// 'ocenka-hour-3': null,
				// 'premiya-3': null,
				// 'ocenka-hour-2': null,
				// 'premiya-2': null,
				if_kurit: null,
				oborot_sp_last_monht_bolee: null,
				oborot_sp_last_monht_menee: null,
				bonus_proc_from_oborot: null,
				pay_from_day_oborot_bolee: null,
			},
		};
	},

	// props: {
	// 	sp: [String, Number],
	// 	dolgnost: {
	// 		type: String, // default: 'all', required: false
	// 	},
	// },

	created() {
		console.log('created', 'dolgnost-pays-form-add');
	},

	computed: {},

	// определяйте методы в объекте `methods`
	methods: {
		...mapActions(['action__addNewItem', 'actions_getItems']),
		...mapGetters(['getterSpNow', 'getterDolgnostNow']),

		scrollTo(idd, sec) {
			smoothScrollTo(document.getElementById(idd).offsetTop, sec * 1000);
			// smoothScrollTo(document.getElementById('form-add-pays').offsetTop, 3000);
		},

		async method_submitChanges() {
			event.preventDefault();

			this.show_button_submit = false;

			let d = this.form_add;
			d.sale_point = this.getterSpNow();
			d.dolgnost = this.getterDolgnostNow();

			console.log('method_submitChanges', d);

			// let put_uri = '';
			// for (let kk in this.form_add) {
			// 	if (this.form_add[kk] == null) continue;
			// 	if (put_uri != '') put_uri += '&';
			// 	put_uri += kk + '=' + this.form_add[kk];
			// }
			// console.log(put_uri);

			let result = await this.action__addNewItem({ module: '071.set_oplata', data: d });
			console.log('res2', result);

			if (result.status == 'error') {
				this.show_tool_tip_danger_text = result.html;
				this.show_tool_tip_danger = true;
				this.show_tool_tip = false;
			} else {
				await this.actions_getItems({
					var1: 'dolgnosti_pays',
					module: '071.set_oplata',
					sort: 'date_desc',
					show: 'all',
				});

				this.showFormAddPays = false;
				this.showFormAddPaysButon = true;
			}

			// alert('добавлено, уже в таблице');

			// this.keypole++;
		},
	},

	async mounted() {},
};
</script>

<style scoped>
.big-form {
	/*background-color: rgba(255, 255, 255, 0.4);*/
	/* background-color: lightblue; */
	/*border: none;*/
	border: 2px solid blue;
	border-radius: 10px;
	box-shadow: 10px grba(0, 0, 0, 0.5);
	padding: 10px 5px;
	margin-bottom: 1rem;
}

.show_form-enter {
	opacity: 0;
}
.show_form-enter-active {
	transition: 2s;
}
.show_form-enter-to {
}
.show_form-leave {
}
.show_form-leave-active {
	transition: 2s;
}
.show_form-leave-to {
	opacity: 0;
}
</style>
